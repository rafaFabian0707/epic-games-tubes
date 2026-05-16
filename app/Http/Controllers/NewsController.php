<?php

namespace App\Http\Controllers;

use App\Models\News;

class NewsController extends Controller
{
    public function index()
    {
        $newsList = News::where('is_active', true)
            ->latest('news_id')   // ← ganti dari created_at ke news_id
            ->paginate(12);

        return view('news.index', compact('newsList'));
    }

    public function show(int $id)
    {
        $article = News::where('news_id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        return view('news.show', compact('article'));
    }
}
