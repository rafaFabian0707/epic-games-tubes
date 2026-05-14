<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Genre;
use App\Models\Feature;
use App\Models\Platform;
use Illuminate\Http\Request;

/**
 * GameController — Mengelola tampilan katalog & detail game (Guest + User)
 *
 * Semua method di sini bersifat READ-ONLY dari sisi Laravel.
 * Operasi tulis (beli, wishlist) ditangani controller lain.
 */
class GameController extends Controller
{
    // =========================================================
    // INDEX — Halaman katalog /store
    // =========================================================

    public function index(Request $request)
    {
        $query = Game::active()
            ->with(['publisher', 'discounts', 'platforms', 'ageRating'])
            ->baseGame();

        // --- Filter: Genre ---
        if ($request->filled('genre')) {
            $query->whereHas('genres', function ($q) use ($request) {
                $q->where('genre_id', $request->genre);
            });
        }

        // --- Filter: Feature ---
        if ($request->filled('feature')) {
            $query->whereHas('features', function ($q) use ($request) {
                $q->where('feature_id', $request->feature);
            });
        }

        // --- Filter: Platform (BARU v4.0) ---
        if ($request->filled('platform')) {
            $query->whereHas('platforms', function ($q) use ($request) {
                $q->where('platform_id', $request->platform);
            });
        }

        // --- Filter: Harga ---
        if ($request->filled('price')) {
            match ($request->price) {
                'free'  => $query->free(),
                'under10'  => $query->where('base_price', '<=', 10)->where('base_price', '>', 0),
                'under30'  => $query->where('base_price', '<=', 30)->where('base_price', '>', 0),
                'under60'  => $query->where('base_price', '<=', 60)->where('base_price', '>', 0),
                default => null,
            };
        }

        // --- Filter: Info badge (First_Run, Now_On_Epic, Trial_Available) ---
        if ($request->filled('info')) {
            $query->where('info', $request->info);
        }

        // --- Sort ---
        match ($request->get('sort', 'newest')) {
            'price_asc'  => $query->orderBy('base_price', 'asc'),
            'price_desc' => $query->orderBy('base_price', 'desc'),
            'rating'     => $query->orderByDesc('avg_rating'),
            'name'       => $query->orderBy('title', 'asc'),
            default      => $query->orderByDesc('created_at'),   // newest
        };

        $games    = $query->paginate(24)->withQueryString();
        $genres   = Genre::orderBy('name')->get();
        $features = Feature::orderBy('name')->get();
        $platforms = Platform::orderBy('platform')->get();

        return view('games.index', compact('games', 'genres', 'features', 'platforms'));
    }

    // =========================================================
    // SEARCH — /store/search?q=keyword
    // =========================================================

    public function search(Request $request)
    {
        $keyword = trim($request->get('q', ''));
        $games   = collect();

        if (strlen($keyword) >= 2) {
            /**
             * PENTING: FULLTEXT index di v4.0 menggunakan kolom:
             *   title, main_desc, desc
             * BUKAN: title, description (kolom description sudah DIHAPUS)
             *
             * Pastikan migration sudah menjalankan:
             *   $table->fullText(['title', 'main_desc', 'desc']);
             */
            $games = Game::active()
                ->with(['publisher', 'discounts', 'platforms'])
                ->whereRaw(
                    'MATCH(title, main_desc, `desc`) AGAINST(? IN BOOLEAN MODE)',
                    [$keyword . '*']
                )
                ->limit(40)
                ->get();
        }

        return view('games.search', compact('games', 'keyword'));
    }

    // =========================================================
    // SHOW — Halaman detail /game/{id}
    // =========================================================

    public function show(int $id)
    {
        /**
         * Eager load semua relasi yang dibutuhkan halaman detail.
         * Urutan ini penting untuk menghindari N+1 query.
         */
        $game = Game::active()
            ->with([
                'publisher',
                'developer',
                'genres',
                'features',
                'tags',
                'platforms',           // BARU v4.0 — platform badges
                'ageRating',           // BARU v4.0 — age rating
                'systemRequirements',
                'achievements',
                'discounts',
                'socialLinks',
                'criticReviews',
                'children' => fn ($q) => $q->where('is_active', true)
                                           ->with('discounts'),
            ])
            ->where('game_id', $id)
            ->firstOrFail();

        // Ambil game terkait berdasarkan genre yang sama (max 6)
        $relatedGames = Game::active()
            ->with(['publisher', 'discounts'])
            ->whereHas('genres', function ($q) use ($game) {
                $q->whereIn('genre_id', $game->genres->pluck('genre_id'));
            })
            ->where('game_id', '!=', $id)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return view('games.show', compact('game', 'relatedGames'));
    }
}
