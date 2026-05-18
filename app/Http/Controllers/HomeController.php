<?php
namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Genre;
use App\Models\News;

class HomeController extends Controller
{
    public function index()
    {
        // Hero carousel — top rated active games
        $featuredGames = Game::active()->baseGame()
            ->with(['publisher', 'discounts', 'platforms', 'ageRating'])
            ->whereNotNull('cover_image_url')
            ->orderByDesc('avg_rating')
            ->limit(6)->get();

        // "Temukan Sesuatu yang Baru" — newest active games
        $newGames = Game::active()->baseGame()
            ->with(['publisher', 'discounts', 'platforms', 'ageRating'])
            ->whereNotNull('cover_image_url')
            ->orderByDesc('game_id')
            ->limit(10)->get();

        // "Diskon Unggulan" — games with active discounts
        $discountedGames = Game::active()->baseGame()
            ->with(['publisher', 'discounts', 'platforms', 'ageRating'])
            ->whereNotNull('cover_image_url')
            ->whereHas('discounts', function ($q) {
                $q->where('is_active', true)
                  ->where('start_date', '<=', now())
                  ->where('end_date', '>=', now());
            })
            ->orderByDesc('avg_rating')
            ->limit(10)->get();

        // Fallback jika belum ada diskon (dev awal)
        if ($discountedGames->isEmpty()) {
            $discountedGames = Game::active()->baseGame()
                ->with(['publisher', 'discounts', 'platforms', 'ageRating'])
                ->whereNotNull('cover_image_url')
                ->orderByDesc('avg_rating')
                ->limit(10)->get();
        }

        // "Game Gratis" section
        $freeGames = Game::active()->free()
            ->with(['publisher', 'platforms', 'ageRating'])
            ->whereNotNull('cover_image_url')
            ->inRandomOrder()
            ->limit(4)->get();

        // Berita terbaru (jika ada)
        $latestNews = News::latest('news_id')->limit(3)->get();

        return view('home', compact(
            'featuredGames',
            'newGames',
            'discountedGames',
            'freeGames',
            'latestNews'
        ));
    }
}
