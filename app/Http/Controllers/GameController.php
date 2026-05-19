<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Genre;
use App\Models\Feature;
use App\Models\Platform;
use Illuminate\Http\Request;

class GameController extends Controller
{
    // =========================================================
    // JELAJAHI — Browse Games
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
        // SEARCH KEYWORD
        // =====================================================

        if ($request->filled('q')) {

            $keyword = trim($request->q);

            $query->where(function ($q) use ($keyword) {

                $q->whereRaw(
                    "MATCH(title, main_desc, `desc`)
                     AGAINST(? IN NATURAL LANGUAGE MODE)",
                    [$keyword]
                )

                // fallback LIKE search
                ->orWhere('title', 'LIKE', "%{$keyword}%");
            });
        }

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
        // =========================================================
// FILTER: FEATURE
// =========================================================

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

$features = Feature::orderBy('name')->get();

$platforms = Platform::orderBy('platform')->get();

// =====================================================
// RETURN VIEW
// =====================================================

return view('jelajahi', compact(
    'games',
    'genres',
    'features',
    'platforms'
));
    }

    // =========================================================
    // STORE
    // =========================================================

    public function index(Request $request)
    {
        $query = Game::active()
            ->with([
                'publisher',
                'discounts',
                'platforms',
                'ageRating'
            ])
            ->baseGame();

        // =====================================================
        // SEARCH
        // =====================================================

        if ($request->filled('q')) {

            $keyword = trim($request->q);

            $query->where(function ($q) use ($keyword) {

                $q->whereRaw(
                    "MATCH(title, main_desc, `desc`)
                     AGAINST(? IN NATURAL LANGUAGE MODE)",
                    [$keyword]
                )

                ->orWhere('title', 'LIKE', "%{$keyword}%");
            });
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
        // FILTER: INFO
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

        $games = $query
            ->paginate(24)
            ->withQueryString();

        $genres = Genre::orderBy('name')->get();

$genres = Genre::orderBy('name')->get();

$features = Feature::orderBy('name')->get();

$platforms = Platform::orderBy('platform')->get();

return view('jelajahi', compact(
    'games',
    'genres',
    'features',
    'platforms'
));
    }

    // =========================================================
    // SEARCH PAGE
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
                    'ageRating'
                ])
                ->where(function ($q) use ($keyword) {

                    $q->whereRaw(
                        "MATCH(title, main_desc, `desc`)
                         AGAINST(? IN NATURAL LANGUAGE MODE)",
                        [$keyword]
                    )

                    ->orWhere('title', 'LIKE', "%{$keyword}%");
                })
                ->limit(40)
                ->get();
        }

        return view('games.search', compact(
            'games',
            'keyword'
        ));
    }

    // =========================================================
    // DETAIL GAME
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
            'parentGame',
        ])
        ->where('game_id', $id)
        ->firstOrFail();

    /*
    |--------------------------------------------------------------------------
    | Ambil root parent
    |--------------------------------------------------------------------------
    | Kalau yang dibuka base game:
    | root = game_id dia sendiri
    |
    | Kalau yang dibuka edition / addon:
    | root = parent_game_id
    */
    $familyRootId = $game->parent_game_id ?: $game->game_id;

    /*
    |--------------------------------------------------------------------------
    | Ambil semua game yang satu keluarga berdasarkan parent_game_id
    |--------------------------------------------------------------------------
    */
    $familyItems = Game::active()
        ->with(['discounts'])
        ->where('parent_game_id', $familyRootId)
        ->where('game_id', '!=', $game->game_id)
        ->orderBy('title')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Pisahkan edition/bundle dan add-on
    |--------------------------------------------------------------------------
    */
    $editions = $familyItems->whereIn('game_type', [
        'edition',
        'bundle',
    ]);

    $addons = $familyItems->where('game_type', 'addon');

    $familyTitle = optional($game->parentGame)->title ?? $game->title;

    /*
    |--------------------------------------------------------------------------
    | Related Games
    |--------------------------------------------------------------------------
    */
    $relatedGames = Game::active()
        ->with([
            'publisher',
            'discounts'
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
        'relatedGames',
        'editions',
        'addons',
        'familyTitle'
    ));
}

    public function addons(int $id)
{
    $game = Game::active()
        ->with([
            'achievements',
            'parentGame',
        ])
        ->where('game_id', $id)
        ->firstOrFail();

    $familyRootId = $game->parent_game_id ?: $game->game_id;

    $addons = Game::active()
        ->with(['discounts'])
        ->where('parent_game_id', $familyRootId)
        ->where('game_id', '!=', $game->game_id)
        ->where('game_type', 'addon')
        ->orderBy('title')
        ->get();

    // Supaya file resources/views/games/addon.blade.php tetap bisa pakai $game->children
    $game->setRelation('children', $addons);

    return view('games.addon', compact('game'));
}

public function achievements(int $id)
{
    $game = Game::active()
        ->with([
            'achievements',
            'parentGame',
        ])
        ->where('game_id', $id)
        ->firstOrFail();

    $familyRootId = $game->parent_game_id ?: $game->game_id;

    $familyItems = Game::active()
        ->with(['discounts'])
        ->where('parent_game_id', $familyRootId)
        ->where('game_id', '!=', $game->game_id)
        ->get();

    // Supaya tab Add-Ons tetap muncul walaupun yang dibuka edition/addon
    $game->setRelation('children', $familyItems);

    return view('games.achievements', compact('game'));
}
}