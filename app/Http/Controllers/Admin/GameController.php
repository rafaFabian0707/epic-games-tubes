<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Age;
use App\Models\Developer;
use App\Models\Feature;
use App\Models\Game;
use App\Models\Genre;
use App\Models\Platform;
use App\Models\Publisher;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Admin\GameController — CRUD penuh untuk tabel games
 *
 * SKEMA v4.0:
 *  - Kolom yang dipakai  : title, info, media_url, main_desc, announce, desc,
 *                          icon_url, base_price, release_date, publisher_id,
 *                          developer_id, cover_image_url, game_type, parent_game_id,
 *                          avg_rating, refund_type, is_active
 *  - Kolom yang DIHAPUS  : description, addon_type, critic_score, critic_count,
 *                          refund_policy, age_rating  ← jangan pernah dipakai
 *  - Tabel pivot baru    : game_platform (sync platforms[])
 *  - Tabel baru          : age (1:1 dengan game, dikelola lewat ageRating relation)
 *
 * CATATAN DEPENDENSI:
 *  - Model Age: primaryKey harus 'age_id', timestamps harus true.
 *    Jika Age.php belum diperbaiki, lihat patch di bawah method destroy().
 */
class GameController extends Controller
{
    // =========================================================
    // INDEX — Daftar semua game dengan pagination
    // GET /admin/games
    // =========================================================

    public function index(Request $request)
    {
        $query = Game::with(['publisher', 'platforms', 'ageRating'])
            ->orderByDesc('game_id');

        // Filter pencarian sederhana
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'LIKE', "%{$q}%")
                    ->orWhere('main_desc', 'LIKE', "%{$q}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        // Filter game_type
        if ($request->filled('type')) {
            $query->where('game_type', $request->type);
        }

        $games = $query->paginate(20)->withQueryString();

        return view('admin.games.index', compact('games'));
    }

    // =========================================================
    // CREATE — Form tambah game baru
    // GET /admin/games/create
    // =========================================================

    public function create()
    {
        $publishers  = Publisher::orderBy('name')->get();
        $developers  = Developer::orderBy('name')->get();
        $genres      = Genre::orderBy('name')->get();
        $features    = Feature::orderBy('name')->get();
        $tags        = Tag::orderBy('label')->get();
        $platforms   = Platform::orderBy('platform')->get();

        // Daftar game yang bisa jadi parent (hanya base_game, bukan dirinya sendiri)
        $parentGames = Game::active()->where('game_type', 'base_game')
            ->orderBy('title')
            ->get(['game_id', 'title']);

        return view('admin.games.create', compact(
            'publishers', 'developers', 'genres', 'features',
            'tags', 'platforms', 'parentGames'
        ));
    }

    // =========================================================
    // STORE — Simpan game baru ke database
    // POST /admin/games
    // =========================================================

    public function store(Request $request)
    {
        $validated = $this->validateGame($request);

        DB::transaction(function () use ($request, $validated) {

            // 1. Buat record game utama
            $game = Game::create([
                'title'           => $validated['title'],
                'info'            => $validated['info'] ?? null,
                'media_url'       => $validated['media_url'] ?? null,
                'main_desc'       => $validated['main_desc'] ?? null,
                'announce'        => $validated['announce'] ?? null,
                'desc'            => $validated['desc'] ?? null,
                'icon_url'        => $validated['icon_url'] ?? null,
                'base_price'      => $validated['base_price'],
                'release_date'    => $validated['release_date'] ?? null,
                'publisher_id'    => $validated['publisher_id'] ?? null,
                'developer_id'    => $validated['developer_id'] ?? null,
                'cover_image_url' => $validated['cover_image_url'] ?? null,
                'game_type'       => $validated['game_type'],
                'parent_game_id'  => $validated['parent_game_id'] ?? null,
                'avg_rating'      => $validated['avg_rating'] ?? null,
                'refund_type'     => $validated['refund_type'] ?? null,
                'is_active'       => $request->boolean('is_active', true),
            ]);

            // 2. Sync relasi pivot
            $game->genres()->sync($request->input('genres', []));
            $game->features()->sync($request->input('features', []));
            $game->tags()->sync($request->input('tags', []));
            $game->platforms()->sync($request->input('platforms', []));

            // 3. Simpan Age Rating jika diisi
            $this->saveAgeRating($game, $request);
        });

        return redirect()->route('admin.games.index')
            ->with('success', 'Game berhasil ditambahkan.');
    }

    // =========================================================
    // EDIT — Form edit game yang sudah ada
    // GET /admin/games/{game}/edit
    // =========================================================

    public function edit(Game $game)
    {
        // Eager load semua relasi yang dibutuhkan form edit
        $game->load([
            'publisher', 'developer', 'genres', 'features',
            'tags', 'platforms', 'ageRating',
        ]);

        $publishers  = Publisher::orderBy('name')->get();
        $developers  = Developer::orderBy('name')->get();
        $genres      = Genre::orderBy('name')->get();
        $features    = Feature::orderBy('name')->get();
        $tags        = Tag::orderBy('label')->get();
        $platforms   = Platform::orderBy('platform')->get();

        $parentGames = Game::active()
            ->where('game_type', 'base_game')
            ->where('game_id', '!=', $game->game_id)
            ->orderBy('title')
            ->get(['game_id', 'title']);

        // Kumpulkan ID yang sudah ter-select untuk pre-check di form
        $selectedGenres   = $game->genres->pluck('genre_id')->toArray();
        $selectedFeatures = $game->features->pluck('feature_id')->toArray();
        $selectedTags     = $game->tags->pluck('tag_id')->toArray();
        $selectedPlatforms = $game->platforms->pluck('platform_id')->toArray();

        return view('admin.games.edit', compact(
            'game', 'publishers', 'developers', 'genres', 'features',
            'tags', 'platforms', 'parentGames',
            'selectedGenres', 'selectedFeatures', 'selectedTags', 'selectedPlatforms'
        ));
    }

    // =========================================================
    // UPDATE — Simpan perubahan game
    // PUT /admin/games/{game}
    // =========================================================

    public function update(Request $request, Game $game)
    {
        $validated = $this->validateGame($request, $game->game_id);

        DB::transaction(function () use ($request, $validated, $game) {

            // 1. Update kolom utama game
            $game->update([
                'title'           => $validated['title'],
                'info'            => $validated['info'] ?? null,
                'media_url'       => $validated['media_url'] ?? null,
                'main_desc'       => $validated['main_desc'] ?? null,
                'announce'        => $validated['announce'] ?? null,
                'desc'            => $validated['desc'] ?? null,
                'icon_url'        => $validated['icon_url'] ?? null,
                'base_price'      => $validated['base_price'],
                'release_date'    => $validated['release_date'] ?? null,
                'publisher_id'    => $validated['publisher_id'] ?? null,
                'developer_id'    => $validated['developer_id'] ?? null,
                'cover_image_url' => $validated['cover_image_url'] ?? null,
                'game_type'       => $validated['game_type'],
                'parent_game_id'  => $validated['parent_game_id'] ?? null,
                'avg_rating'      => $validated['avg_rating'] ?? null,
                'refund_type'     => $validated['refund_type'] ?? null,
                'is_active'       => $request->boolean('is_active', true),
            ]);

            // 2. Sync pivot — sync() otomatis hapus yang tidak ada di array
            $game->genres()->sync($request->input('genres', []));
            $game->features()->sync($request->input('features', []));
            $game->tags()->sync($request->input('tags', []));
            $game->platforms()->sync($request->input('platforms', []));

            // 3. Update Age Rating
            $this->saveAgeRating($game, $request);
        });

        return redirect()->route('admin.games.index')
            ->with('success', "Game \"{$game->title}\" berhasil diperbarui.");
    }

    // =========================================================
    // DESTROY — Nonaktifkan game (soft-deactivate)
    // DELETE /admin/games/{game}
    //
    // Alasan pakai deactivate bukan hard delete:
    //  - transaction_details masih merujuk ke game_id (FK cascadeOnDelete
    //    akan hapus histori transaksi → merusak laporan keuangan)
    //  - library user juga merujuk ke game_id
    //  - Deactivate lebih aman: game tidak muncul di toko tapi data integritas terjaga.
    //  - Untuk hard delete, gunakan method forceDelete() terpisah jika memang perlu.
    // =========================================================

    public function destroy(Game $game)
    {
        $game->update(['is_active' => false]);

        return redirect()->route('admin.games.index')
            ->with('success', "Game \"{$game->title}\" telah dinonaktifkan.");
    }

    // =========================================================
    // HELPER PRIVATE — Validasi input game (dipakai store & update)
    // =========================================================

    private function validateGame(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title'           => ['required', 'string', 'max:200'],
            'info'            => ['nullable', 'in:First_Run,Now_On_Epic,Trial_Available'],
            'media_url'       => ['nullable', 'url', 'max:255'],
            'main_desc'       => ['nullable', 'string', 'max:5000'],
            'announce'        => ['nullable', 'string', 'max:2000'],
            'desc'            => ['nullable', 'string'],
            'icon_url'        => ['nullable', 'url', 'max:255'],
            'base_price'      => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'release_date'    => ['nullable', 'date'],
            'publisher_id'    => ['nullable', 'exists:publishers,publisher_id'],
            'developer_id'    => ['nullable', 'exists:developers,developer_id'],
            'cover_image_url' => ['nullable', 'url', 'max:255'],
            'game_type'       => ['required', 'in:base_game,edition,addon,aplikasi,editor,langganan,pengalaman,bundle,demo'],
            'parent_game_id'  => ['nullable', 'exists:games,game_id'],
            'avg_rating'      => ['nullable', 'numeric', 'min:0', 'max:5'],
            'refund_type'     => ['nullable', 'in:refundable,self_refundable,non_refundable'],
            'is_active'       => ['nullable', 'boolean'],

            // Pivot — array of ID
            'genres'    => ['nullable', 'array'],
            'genres.*'  => ['integer', 'exists:genres,genre_id'],
            'features'  => ['nullable', 'array'],
            'features.*'=> ['integer', 'exists:features,feature_id'],
            'tags'      => ['nullable', 'array'],
            'tags.*'    => ['integer', 'exists:tags,tag_id'],
            'platforms' => ['nullable', 'array'],
            'platforms.*'=> ['integer', 'exists:platform,platform_id'],

            // Age Rating (opsional, 1:1)
            'age'      => ['nullable', 'string', 'max:10'],
            'age_desc' => ['nullable', 'string', 'max:50'],
        ], [
            'title.required'      => 'Judul game wajib diisi.',
            'base_price.required' => 'Harga dasar wajib diisi.',
            'base_price.numeric'  => 'Harga harus berupa angka.',
            'game_type.required'  => 'Tipe game wajib dipilih.',
        ]);
    }

    // =========================================================
    // HELPER PRIVATE — Simpan/update age rating (1:1 dengan game)
    //
    // CATATAN PENTING: Method ini butuh Age model yang benar:
    //   protected $primaryKey = 'age_id';   (bukan 'id')
    //   public $timestamps    = true;        (bukan false)
    //   protected $fillable   = ['game_id', 'age', 'desc'];
    //   public function game() { return $this->belongsTo(Game::class, ...) }
    //
    // Jika Age.php belum diperbaiki, jalankan patch:
    //   app/Models/Age.php → lihat komentar di bawah ini.
    // =========================================================

    private function saveAgeRating(Game $game, Request $request): void
    {
        $age     = $request->input('age');
        $ageDesc = $request->input('age_desc');

        if (! empty($age)) {
            // updateOrCreate berdasarkan game_id (relasi 1:1)
            Age::updateOrCreate(
                ['game_id' => $game->game_id],
                ['age' => $age, 'desc' => $ageDesc]
            );
        } else {
            // Jika input dikosongkan, hapus age rating yang ada
            Age::where('game_id', $game->game_id)->delete();
        }
    }
}

/*
|--------------------------------------------------------------------------
| PATCH WAJIB — app/Models/Age.php
|--------------------------------------------------------------------------
| Model Age.php yang ada di project masih salah. Sebelum controller ini
| bisa jalan normal, PERBAIKI file app/Models/Age.php menjadi:
|
| <?php
| namespace App\Models;
| use Illuminate\Database\Eloquent\Model;
|
| class Age extends Model
| {
|     protected $table      = 'age';
|     protected $primaryKey = 'age_id';   // ← FIX: bukan 'id'
|     public $timestamps    = true;        // ← FIX: migration punya timestamps()
|
|     protected $fillable = ['game_id', 'age', 'desc'];
|
|     public function game()
|     {
|         return $this->belongsTo(Game::class, 'game_id', 'game_id');
|     }
| }
|--------------------------------------------------------------------------
*/
