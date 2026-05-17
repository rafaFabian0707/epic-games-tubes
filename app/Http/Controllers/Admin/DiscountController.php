<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Game;
use Illuminate\Http\Request;

/**
 * Admin\DiscountController — CRUD diskon game
 *
 * SKEMA tabel `discounts`:
 *   discount_id   INT PK AUTO_INCREMENT
 *   game_id       INT FK → games.game_id (cascadeOnDelete)
 *   discount_pct  DECIMAL(5,2)      ← persentase diskon, misal 20.00 = 20%
 *   start_date    DATETIME
 *   end_date      DATETIME
 *   is_active     TINYINT(1) DEFAULT 1
 *   created_at, updated_at
 *
 * ARSITEKTUR:
 *  - Harga final game dihitung oleh accessor `getFinalPriceAttribute` di Game model.
 *  - Controller ini TIDAK perlu menghitung harga — cukup kelola record diskon.
 *  - Validasi mencegah start_date >= end_date.
 *  - Satu game bisa punya banyak diskon, tapi accessor mengambil yang
 *    is_active=1 dan paling besar discount_pct-nya.
 */
class DiscountController extends Controller
{
    // =========================================================
    // INDEX — Daftar semua diskon
    // GET /admin/discounts
    // =========================================================

    public function index(Request $request)
    {
        $query = Discount::with(['game.publisher'])
            ->orderByDesc('discount_id');

        // Filter pencarian berdasarkan judul game
        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('game', fn($sub) => $sub->where('title', 'LIKE', "%{$q}%"));
        }

        // Filter status diskon
        if ($request->filled('status')) {
            match ($request->status) {
                'active'   => $query->where('is_active', true)
                                    ->where('start_date', '<=', now())
                                    ->where('end_date', '>=', now()),
                'inactive' => $query->where('is_active', false),
                'expired'  => $query->where('end_date', '<', now()),
                'upcoming' => $query->where('start_date', '>', now()),
                default    => null,
            };
        }

        $discounts = $query->paginate(20)->withQueryString();

        return view('admin.discounts.index', compact('discounts'));
    }

    // =========================================================
    // CREATE — Form tambah diskon baru
    // GET /admin/discounts/create
    // =========================================================

    public function create()
    {
        // Hanya tampilkan game aktif dengan tipe base_game untuk dropdown
        $games = Game::active()
            ->orderBy('title')
            ->get(['game_id', 'title', 'base_price']);

        return view('admin.discounts.create', compact('games'));
    }

    // =========================================================
    // STORE — Simpan diskon baru
    // POST /admin/discounts
    // =========================================================

    public function store(Request $request)
    {
        $validated = $this->validateDiscount($request);

        Discount::create($validated);

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Diskon berhasil ditambahkan.');
    }

    // =========================================================
    // EDIT — Form edit diskon
    // GET /admin/discounts/{discount}/edit
    // =========================================================

    public function edit(Discount $discount)
    {
        $discount->load('game');

        $games = Game::active()
            ->orderBy('title')
            ->get(['game_id', 'title', 'base_price']);

        return view('admin.discounts.edit', compact('discount', 'games'));
    }

    // =========================================================
    // UPDATE — Simpan perubahan diskon
    // PUT /admin/discounts/{discount}
    // =========================================================

    public function update(Request $request, Discount $discount)
    {
        $validated = $this->validateDiscount($request, $discount);

        $discount->update($validated);

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Diskon berhasil diperbarui.');
    }

    // =========================================================
    // DESTROY — Hapus diskon permanen
    // DELETE /admin/discounts/{discount}
    // =========================================================

    public function destroy(Discount $discount)
    {
        $discount->delete();

        return redirect()->route('admin.discounts.index')
            ->with('success', 'Diskon berhasil dihapus.');
    }

    // =========================================================
    // HELPER PRIVATE — Validasi input diskon
    // =========================================================

    private function validateDiscount(Request $request, ?Discount $existing = null): array
    {
        return $request->validate([
            'game_id'      => ['required', 'exists:games,game_id'],
            'discount_pct' => ['required', 'numeric', 'min:1', 'max:100'],
            'start_date'   => ['required', 'date'],
            'end_date'     => ['required', 'date', 'after:start_date'],
            'is_active'    => ['nullable', 'boolean'],
        ], [
            'game_id.required'      => 'Pilih game untuk diskon ini.',
            'game_id.exists'        => 'Game tidak ditemukan.',
            'discount_pct.required' => 'Persentase diskon wajib diisi.',
            'discount_pct.min'      => 'Diskon minimal 1%.',
            'discount_pct.max'      => 'Diskon maksimal 100%.',
            'start_date.required'   => 'Tanggal mulai wajib diisi.',
            'end_date.required'     => 'Tanggal berakhir wajib diisi.',
            'end_date.after'        => 'Tanggal berakhir harus setelah tanggal mulai.',
        ]) + ['is_active' => $request->boolean('is_active', true)];
    }
}
