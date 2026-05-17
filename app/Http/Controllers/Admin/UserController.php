<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * Admin\UserController — Manajemen akun user
 *
 * SKEMA tabel `users`:
 *   user_id        INT PK AUTO_INCREMENT
 *   username       VARCHAR(50) UNIQUE
 *   email          VARCHAR(100) UNIQUE
 *   password       VARCHAR(255)
 *   full_name      VARCHAR(100) NULLABLE
 *   is_admin       TINYINT(1) DEFAULT 0
 *   is_active      TINYINT(1) DEFAULT 1
 *   remember_token VARCHAR(100)
 *   created_at, updated_at
 *
 * BATASAN KEAMANAN:
 *  - Admin tidak bisa menghapus atau menonaktifkan dirinya sendiri.
 *  - Admin tidak bisa mengubah passwordnya sendiri dari panel ini
 *    (harus lewat halaman profil masing-masing — sesuai flow Breeze).
 *  - Kolom yang sudah DIHAPUS di v4.0: country, avatar_url.
 *    Controller ini TIDAK menggunakan kolom tersebut.
 */
class UserController extends Controller
{
    // =========================================================
    // INDEX — Daftar semua user dengan search dan filter
    // GET /admin/users
    // =========================================================

    public function index(Request $request)
    {
        $query = User::orderByDesc('user_id');

        // Search berdasarkan username, email, atau full_name
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('username', 'LIKE', "%{$q}%")
                    ->orWhere('email', 'LIKE', "%{$q}%")
                    ->orWhere('full_name', 'LIKE', "%{$q}%");
            });
        }

        // Filter is_active
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter is_admin
        if ($request->filled('role')) {
            $query->where('is_admin', $request->role === 'admin');
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    // =========================================================
    // SHOW — Detail user + riwayat transaksi & library
    // GET /admin/users/{user}
    // =========================================================

    public function show(User $user)
    {
        // Load relasi ringkasan (tidak load semua game untuk hemat query)
        $user->load([
            'transactions' => fn($q) => $q->whereNotNull('completed_at')
                                          ->latest('completed_at')
                                          ->limit(10),
            'library'      => fn($q) => $q->with('game:game_id,title,cover_image_url')
                                          ->latest('acquired_at')
                                          ->limit(10),
        ]);

        $transactionCount = $user->transactions()->whereNotNull('completed_at')->count();
        $totalSpent       = $user->transactions()->whereNotNull('completed_at')->sum('total_amount');
        $libraryCount     = $user->library()->count();
        $wishlistCount    = $user->wishlists()->count();

        return view('admin.users.show', compact(
            'user', 'transactionCount', 'totalSpent', 'libraryCount', 'wishlistCount'
        ));
    }

    // =========================================================
    // EDIT — Form edit data user (admin only)
    // GET /admin/users/{user}/edit
    // =========================================================

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // =========================================================
    // UPDATE — Update data user (username, email, full_name, is_admin, is_active)
    // PUT /admin/users/{user}
    // =========================================================

    public function update(Request $request, User $user)
    {
        // Cegah admin mengubah data dirinya sendiri lewat panel ini
        // (gunakan halaman profil untuk itu)
        if (Auth::id() === $user->user_id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Tidak bisa mengedit akun sendiri dari panel ini.');
        }

        $validated = $request->validate([
            'username'  => [
                'required', 'string', 'max:50',
                Rule::unique('users', 'username')->ignore($user->user_id, 'user_id'),
            ],
            'email'     => [
                'required', 'email', 'max:100',
                Rule::unique('users', 'email')->ignore($user->user_id, 'user_id'),
            ],
            'full_name' => ['nullable', 'string', 'max:100'],
            'is_admin'  => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique'   => 'Username sudah dipakai akun lain.',
            'email.required'    => 'Email wajib diisi.',
            'email.unique'      => 'Email sudah dipakai akun lain.',
        ]);

        $user->update([
            'username'  => $validated['username'],
            'email'     => $validated['email'],
            'full_name' => $validated['full_name'] ?? null,
            'is_admin'  => $request->boolean('is_admin', false),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "User \"{$user->username}\" berhasil diperbarui.");
    }

    // =========================================================
    // TOGGLE ADMIN — Toggle status is_admin
    // POST /admin/users/{user}/toggle-admin
    // =========================================================

    public function toggleAdmin(User $user)
    {
        // Cegah admin mencabut status admin dirinya sendiri
        if (Auth::id() === $user->user_id) {
            return back()->with('error', 'Tidak bisa mengubah status admin diri sendiri.');
        }

        $user->update(['is_admin' => ! $user->is_admin]);

        $status = $user->is_admin ? 'dijadikan Admin' : 'diubah menjadi User biasa';

        return back()->with('success', "User \"{$user->username}\" berhasil {$status}.");
    }

    // =========================================================
    // TOGGLE ACTIVE — Aktifkan/nonaktifkan user
    // POST /admin/users/{user}/toggle-active
    // =========================================================

    public function toggleActive(User $user)
    {
        // Cegah admin menonaktifkan dirinya sendiri
        if (Auth::id() === $user->user_id) {
            return back()->with('error', 'Tidak bisa menonaktifkan akun sendiri.');
        }

        $user->update(['is_active' => ! $user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "User \"{$user->username}\" berhasil {$status}.");
    }

    // =========================================================
    // DESTROY — Hapus user permanen
    // DELETE /admin/users/{user}
    //
    // PERINGATAN: Menghapus user akan cascade ke:
    //   - transactions → transaction_details (cascade)
    //   - library entries (cascade)
    //   - wishlist entries (cascade)
    //
    // Ini akan menghapus seluruh histori transaksi user.
    // Pertimbangkan pakai toggleActive() sebagai alternatif yang lebih aman.
    // =========================================================

    public function destroy(User $user)
    {
        // Cegah admin menghapus dirinya sendiri
        if (Auth::id() === $user->user_id) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $username = $user->username;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User \"{$username}\" berhasil dihapus beserta seluruh datanya.");
    }
}
