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
    // JELAJAHI — Halaman browse /jelajahi
    // =========================================================

    public function jelajahi(Request $request)
    {
        $query = Game::active()
            ->with(['publisher', 'discounts', 'genres', 'platforms'])
            ->whereNotNull('cover_image_url');

        // --- Filter: game_type ---
        if ($request->filled('type') && $request->type !== 'semua') {
            $query->where('game_type', $request->type);
        }

        // --- Filter: Genre ---
        if ($request->filled('genre')) {
            $query->whereHas('genres', fn ($q) => $q->where('genre_id', $request->genre));
        }

        // --- Filter: Platform ---
        if ($request->filled('platform')) {
            $query->whereHas('platforms', fn ($q) => $q->where('platform.platform_id', $request->platform));
        }

        // --- Filter: Harga ---
        if ($request->filled('price')) {
            if ($request->price === 'free') {
                $query->where(function ($q) { $q->where('base_price', 0)->orWhereNull('base_price'); });
            } elseif ($request->price === 'discount') {
                $query->whereHas('discounts', function ($q) {
                    $q->where('is_active', true)
                      ->where('start_date', '<=', now())
                      ->where('end_date', '>=', now());
                });
            } elseif ($request->price === 'under150') {
                $query->where('base_price', '<=', 150000)->where('base_price', '>', 0);
            } elseif ($request->price === 'under300') {
                $query->where('base_price', '<=', 300000)->where('base_price', '>', 0);
            }
        }

        // --- Sort ---
        $sort = $request->get('sort', 'newest');
        if ($sort === 'price_asc')      { $query->orderBy('base_price', 'asc'); }
        elseif ($sort === 'price_desc') { $query->orderBy('base_price', 'desc'); }
        elseif ($sort === 'rating')     { $query->orderByDesc('avg_rating'); }
        elseif ($sort === 'name')       { $query->orderBy('title', 'asc'); }
        else { $query->orderByDesc('game_id'); }

        $games     = $query->paginate(20)->withQueryString();
        $genres    = Genre::orderBy('name')->get();
        $platforms = Platform::orderBy('platform')->get();

        return view('jelajahi', compact('games', 'genres', 'platforms'));
    }

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
                $q->where('platform.platform_id', $request->platform);
            });
        }

        // --- Filter: Harga ---
        if ($request->filled('price')) {
            if ($request->price === 'free') {
                $query->where(function ($q) { $q->where('base_price', 0)->orWhereNull('base_price'); });
            } elseif ($request->price === 'under150') {
                $query->where('base_price', '<=', 150000)->where('base_price', '>', 0);
            } elseif ($request->price === 'under300') {
                $query->where('base_price', '<=', 300000)->where('base_price', '>', 0);
            } elseif ($request->price === 'under600') {
                $query->where('base_price', '<=', 600000)->where('base_price', '>', 0);
            }
        }

        // --- Filter: Info badge (First_Run, Now_On_Epic, Trial_Available) ---
        if ($request->filled('info')) {
            $query->where('info', $request->info);
        }

        // --- Sort ---
        $sort = $request->get('sort', 'newest');
        if ($sort === 'price_asc')  { $query->orderBy('base_price', 'asc'); }
        elseif ($sort === 'price_desc') { $query->orderBy('base_price', 'desc'); }
        elseif ($sort === 'rating')     { $query->orderByDesc('avg_rating'); }
        elseif ($sort === 'name')       { $query->orderBy('title', 'asc'); }
        else { $query->orderByDesc('created_at'); }

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
            $like  = '%' . $keyword . '%';
            $games = Game::active()
                ->baseGame()
                ->with(['publisher', 'discounts', 'platforms'])
                ->where(function ($q) use ($like) {
                    $q->where('title', 'LIKE', $like)
                      ->orWhere('main_desc', 'LIKE', $like)
                      ->orWhere('desc', 'LIKE', $like);
                })
                ->orderByRaw("
                    CASE
                        WHEN title LIKE ? THEN 0
                        ELSE 1
                    END
                ", [$like])
                ->orderBy('title')
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
                'platforms',
                'ageRating',
                'systemRequirements',
                'achievements',
                'discounts',
                'socialLinks',
                'criticReviews',
                'children' => fn ($q) => $q->where('is_active', true)
                                           ->with(['discounts', 'genres', 'features']),
            ])
            ->where('game_id', $id)
            ->firstOrFail();

        // Ambil game terkait berdasarkan genre yang sama (max 6)
        $relatedGames = Game::active()
            ->with(['publisher', 'discounts'])
            ->whereHas('genres', function ($q) use ($game) {
                $q->whereIn('genres.genre_id', $game->genres->pluck('genre_id')); // ← tambah prefix tabel
            })
            ->where('game_id', '!=', $id)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return view('games.show', compact('game', 'relatedGames'));
    }

    // =========================================================
    // ADDONS — Halaman DLC & Add-Ons /game/{id}/addons
    // =========================================================

    public function addons(int $id)
    {
        $game = Game::active()
            ->with([
                'publisher', 'developer',
                'achievements',
                'children' => fn($q) => $q->where('is_active', true)->with('discounts'),
            ])
            ->where('game_id', $id)
            ->firstOrFail();

        return view('games.addons', compact('game'));
    }

    // =========================================================
    // ACHIEVEMENTS — Halaman Achievements /game/{id}/achievements
    // =========================================================

    public function achievements(int $id)
    {
        $game = Game::active()
            ->with([
                'publisher', 'developer',
                'achievements',
                'children' => fn($q) => $q->where('is_active', true),
            ])
            ->where('game_id', $id)
            ->firstOrFail();

        return view('games.achievements', compact('game'));
    }
}