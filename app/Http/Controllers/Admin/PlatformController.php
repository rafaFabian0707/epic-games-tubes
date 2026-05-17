<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Admin\PlatformController — CRUD master data platform
 *
 * SKEMA tabel `platform`:
 *   platform_id  INT PK AUTO_INCREMENT
 *   platform     VARCHAR(20)           ← nama platform, seharusnya UNIQUE
 *
 * CATATAN: Migration platform saat ini tidak memiliki constraint UNIQUE
 * pada kolom `platform`. Controller ini tetap validasi unique di level
 * aplikasi dengan Rule::unique(). Idealnya migration diperbaiki juga.
 */
class PlatformController extends Controller
{
    // =========================================================
    // INDEX — Daftar semua platform
    // GET /admin/platforms
    // =========================================================

    public function index()
    {
        // Tampilkan platform beserta jumlah game yang terdaftar
        $platforms = Platform::withCount('games')
            ->orderBy('platform')
            ->paginate(20);

        return view('admin.platforms.index', compact('platforms'));
    }

    // =========================================================
    // CREATE — Form tambah platform baru
    // GET /admin/platforms/create
    // =========================================================

    public function create()
    {
        return view('admin.platforms.create');
    }

    // =========================================================
    // STORE — Simpan platform baru
    // POST /admin/platforms
    // =========================================================

    public function store(Request $request)
    {
        $request->validate([
            'platform' => [
                'required',
                'string',
                'max:20',
                // Validasi unique di level aplikasi karena migration belum ada constraint
                Rule::unique('platform', 'platform'),
            ],
        ], [
            'platform.required' => 'Nama platform wajib diisi.',
            'platform.max'      => 'Nama platform maksimal 20 karakter.',
            'platform.unique'   => 'Platform ini sudah terdaftar.',
        ]);

        Platform::create(['platform' => $request->platform]);

        return redirect()->route('admin.platforms.index')
            ->with('success', "Platform \"{$request->platform}\" berhasil ditambahkan.");
    }

    // =========================================================
    // EDIT — Form edit platform
    // GET /admin/platforms/{platform}/edit
    // =========================================================

    public function edit(Platform $platform)
    {
        return view('admin.platforms.edit', compact('platform'));
    }

    // =========================================================
    // UPDATE — Simpan perubahan nama platform
    // PUT /admin/platforms/{platform}
    // =========================================================

    public function update(Request $request, Platform $platform)
    {
        $request->validate([
            'platform' => [
                'required',
                'string',
                'max:20',
                // Abaikan record sendiri saat cek unique
                Rule::unique('platform', 'platform')->ignore($platform->platform_id, 'platform_id'),
            ],
        ], [
            'platform.required' => 'Nama platform wajib diisi.',
            'platform.max'      => 'Nama platform maksimal 20 karakter.',
            'platform.unique'   => 'Nama platform ini sudah dipakai.',
        ]);

        $oldName = $platform->platform;
        $platform->update(['platform' => $request->platform]);

        return redirect()->route('admin.platforms.index')
            ->with('success', "Platform \"{$oldName}\" berhasil diperbarui menjadi \"{$request->platform}\".");
    }

    // =========================================================
    // DESTROY — Hapus platform
    // DELETE /admin/platforms/{platform}
    //
    // Karena game_platform pakai cascadeOnDelete, menghapus platform
    // akan otomatis menghapus semua entry di game_platform.
    // Game-nya sendiri tidak ikut terhapus (hanya asosiasi yang hilang).
    // =========================================================

    public function destroy(Platform $platform)
    {
        // Cegah hapus jika masih dipakai banyak game (threshold: 0 → boleh hapus)
        $gameCount = $platform->games()->count();

        if ($gameCount > 0) {
            return redirect()->route('admin.platforms.index')
                ->with('error', "Platform \"{$platform->platform}\" masih dipakai oleh {$gameCount} game. Hapus asosiasi game terlebih dahulu.");
        }

        $name = $platform->platform;
        $platform->delete();

        return redirect()->route('admin.platforms.index')
            ->with('success', "Platform \"{$name}\" berhasil dihapus.");
    }
}
