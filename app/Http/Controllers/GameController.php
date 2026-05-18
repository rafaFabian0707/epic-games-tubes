<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Genre;
use App\Models\Feature;
use App\Models\Platform;
use Illuminate\Http\Request;

/**
 * GameController
 *
 * Mengelola:
 * - Browse game (/jelajahi)
 * - Store (/store)
 * - Search
 * - Detail game
 */
class GameController extends Controller
{
    // =========================================================
    // JELAJAHI — /jelajahi
    // =========================================================

    public function jelajahi(Request $request)
    {
        $query = Game::active()
            ->with([
                'publisher',
                'discounts',
                'genres',
                'platforms',
                'ageRating',
            ])
            ->whereNotNull('cover_image_url');

        // =====================================================
        // FILTER: GAME TYPE
        // =====================================================

        if ($request->filled('type') && $request->type !== 'semua') {

            $query->where('game_type', $request->type);
        }

        // =====================================================
        // FILTER: GENRE
        // =====================================================

        if ($request->filled('genre')) {

            $query->whereHas('genres', function ($q) use ($request) {

                $q->where('genres.genre_id', $request->genre);
            });
        }

        // =====================================================
        // FILTER: PLATFORM
        // =====================================================

        if ($request->filled('platform')) {

            $query->whereHas('platforms', function ($q) use ($request) {

                $q->where('platform.platform_id', $request->platform);
            });
        }

        // =====================================================
        // FILTER: PRICE
        // =====================================================

        if ($request->filled('price')) {

            match ($request->price) {

                'free' =>

                    $query->where('base_price', 0),

                'discount' =>

                    $query->whereHas('discounts', function ($q) {

                        $q->where('is_active', true)
                            ->where('start_date', '<=', now())
                            ->where('end_date', '>=', now());
                    }),

                'under150' =>

                    $query->where('base_price', '<=', 150000)
                        ->where('base_price', '>', 0),

                'under300' =>

                    $query->where('base_price', '<=', 300000)
                        ->where('base_price', '>', 0),

                default => null,
            };
        }

        // =====================================================
        // SORTING
        // =====================================================

        match ($request->get('sort', 'newest')) {

            'price_asc' =>

                $query->orderBy('base_price', 'asc'),

            'price_desc' =>

                $query->orderBy('base_price', 'desc'),

            'rating' =>

                $query->orderByDesc('avg_rating'),

            'name' =>

                $query->orderBy('title', 'asc'),

            default =>

                $query->orderByDesc('game_id'),
        };

        // =====================================================
        // PAGINATION
        // =====================================================

        $games = $query
            ->paginate(20)
            ->withQueryString();

        // =====================================================
        // FILTER DATA
        // =====================================================

        $genres = Genre::orderBy('name')->get();

        $platforms = Platform::orderBy('platform')->get();

        // =====================================================
        // RETURN VIEW
        // =====================================================

        return view('jelajahi', compact(
            'games',
            'genres',
            'platforms'
        ));
    }

    // =========================================================
    // STORE — /store
    // =========================================================

    public function index(Request $request)
    {
        $query = Game::active()
            ->baseGame()
            ->with([
                'publisher',
                'discounts',
                'platforms',
                'ageRating',
            ]);

        // =====================================================
        // FILTER: GENRE
        // =====================================================

        if ($request->filled('genre')) {

            $query->whereHas('genres', function ($q) use ($request) {

                $q->where('genres.genre_id', $request->genre);
            });
        }

        // =====================================================
        // FILTER: FEATURE
        // =====================================================

        if ($request->filled('feature')) {

            $query->whereHas('features', function ($q) use ($request) {

                $q->where('features.feature_id', $request->feature);
            });
        }

        // =====================================================
        // FILTER: PLATFORM
        // =====================================================

        if ($request->filled('platform')) {

            $query->whereHas('platforms', function ($q) use ($request) {

                $q->where('platform.platform_id', $request->platform);
            });
        }

        // =====================================================
        // FILTER: PRICE
        // =====================================================

        if ($request->filled('price')) {

            match ($request->price) {

                'free' =>

                    $query->free(),

                'under10' =>

                    $query->where('base_price', '<=', 10)
                        ->where('base_price', '>', 0),

                'under30' =>

                    $query->where('base_price', '<=', 30)
                        ->where('base_price', '>', 0),

                'under60' =>

                    $query->where('base_price', '<=', 60)
                        ->where('base_price', '>', 0),

                default => null,
            };
        }

        // =====================================================
        // FILTER: INFO BADGE
        // =====================================================

        if ($request->filled('info')) {

            $query->where('info', $request->info);
        }

        // =====================================================
        // SORTING
        // =====================================================

        match ($request->get('sort', 'newest')) {

            'price_asc' =>

                $query->orderBy('base_price', 'asc'),

            'price_desc' =>

                $query->orderBy('base_price', 'desc'),

            'rating' =>

                $query->orderByDesc('avg_rating'),

            'name' =>

                $query->orderBy('title', 'asc'),

            default =>

                $query->orderByDesc('created_at'),
        };

        // =====================================================
        // PAGINATION
        // =====================================================

        $games = $query
            ->paginate(24)
            ->withQueryString();

        // =====================================================
        // FILTER DATA
        // =====================================================

        $genres = Genre::orderBy('name')->get();

        $features = Feature::orderBy('name')->get();

        $platforms = Platform::orderBy('platform')->get();

        // =====================================================
        // RETURN VIEW
        // =====================================================

        return view('games.index', compact(
            'games',
            'genres',
            'features',
            'platforms'
        ));
    }

    // =========================================================
    // SEARCH — /store/search
    // =========================================================

    public function search(Request $request)
    {
        $keyword = trim($request->get('q', ''));

        $games = collect();

        if (strlen($keyword) >= 2) {

            $games = Game::active()
                ->with([
                    'publisher',
                    'discounts',
                    'platforms',
                    'ageRating',
                ])
                ->whereRaw(
                    'MATCH(title, main_desc, `desc`) AGAINST(? IN BOOLEAN MODE)',
                    [$keyword . '*']
                )
                ->limit(40)
                ->get();
        }

        return view('games.search', compact(
            'games',
            'keyword'
        ));
    }

    // =========================================================
    // SHOW — /game/{id}
    // =========================================================

    public function show(int $id)
    {
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

                'children' => fn ($q) =>

                    $q->where('is_active', true)
                        ->with('discounts'),
            ])
            ->where('game_id', $id)
            ->firstOrFail();

        // =====================================================
        // RELATED GAMES
        // =====================================================

        $relatedGames = Game::active()
            ->with([
                'publisher',
                'discounts',
            ])
            ->whereHas('genres', function ($q) use ($game) {

                $q->whereIn(
                    'genres.genre_id',
                    $game->genres->pluck('genre_id')
                );
            })
            ->where('game_id', '!=', $id)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return view('games.show', compact(
            'game',
            'relatedGames'
        ));
    }
}