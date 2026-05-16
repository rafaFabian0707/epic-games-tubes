@extends('layouts.app')

@section('title', 'Berita — Epic Games')

@section('content')
<div class="min-h-screen bg-gray-950 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ===== PAGE HEADER ===== --}}
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-white tracking-tight mb-2">Epic Games News</h1>
            <p class="text-gray-400 text-sm">Update terbaru dari dunia game dan Epic Games Store.</p>
        </div>

        {{-- ===== EMPTY STATE ===== --}}
        @if ($newsList->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mb-5">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-white mb-2">Belum Ada Berita</h2>
                <p class="text-gray-500 text-sm">Pantau terus halaman ini untuk update terbaru.</p>
            </div>

        {{-- ===== NEWS GRID ===== --}}
        @else

            {{-- Hero article (artikel pertama / terbaru) --}}
            @php $hero = $newsList->first(); @endphp
            <a href="{{ route('news.show', $hero->news_id) }}"
               class="group block bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden mb-8
                       hover:border-gray-700 transition-all duration-300">
                <div class="flex flex-col md:flex-row">
                    {{-- Gambar hero --}}
                    <div class="md:w-1/2 h-56 md:h-72 overflow-hidden bg-gray-800 flex-shrink-0">
                        @if ($hero->cover_url)
                            <img src="{{ $hero->cover_url }}" alt="{{ $hero->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Konten hero --}}
                    <div class="flex-1 p-6 md:p-8 flex flex-col justify-center">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="bg-blue-600 text-white text-xs font-bold px-2.5 py-1 rounded-full">TERBARU</span>
                            @if ($hero->date)
                                <span class="text-gray-500 text-xs">{{ $hero->date }}</span>
                            @endif
                        </div>
                        <h2 class="text-white font-bold text-xl md:text-2xl leading-tight mb-3
                                   group-hover:text-blue-400 transition-colors duration-200">
                            {{ $hero->title }}
                        </h2>
                        @if ($hero->excerpt)
                            <p class="text-gray-400 text-sm leading-relaxed mb-4 line-clamp-3">
                                {{ $hero->excerpt }}
                            </p>
                        @endif
                        <div class="flex items-center gap-2 text-gray-500 text-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $hero->publisher }}
                        </div>
                    </div>
                </div>
            </a>

            {{-- Grid artikel lainnya --}}
            @if ($newsList->count() > 1)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-10">
                    @foreach ($newsList->slice(1) as $article)
                        <a href="{{ route('news.show', $article->news_id) }}"
                           class="group flex flex-col bg-gray-900 border border-gray-800 rounded-xl overflow-hidden
                                  hover:border-gray-700 hover:-translate-y-1 transition-all duration-300">

                            {{-- Gambar --}}
                            <div class="h-44 overflow-hidden bg-gray-800 flex-shrink-0">
                                @if ($article->cover_url)
                                    <img src="{{ $article->cover_url }}" alt="{{ $article->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Konten --}}
                            <div class="flex flex-col flex-1 p-4">
                                @if ($article->date)
                                    <p class="text-gray-500 text-xs mb-2">{{ $article->date }}</p>
                                @endif
                                <h3 class="text-white font-semibold text-sm leading-snug mb-2 line-clamp-2
                                           group-hover:text-blue-400 transition-colors duration-200">
                                    {{ $article->title }}
                                </h3>
                                @if ($article->excerpt)
                                    <p class="text-gray-500 text-xs leading-relaxed line-clamp-2 flex-1">
                                        {{ $article->excerpt }}
                                    </p>
                                @endif
                                <div class="flex items-center gap-1.5 mt-3 pt-3 border-t border-gray-800">
                                    <svg class="w-3 h-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span class="text-gray-600 text-xs">{{ $article->publisher }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- ===== PAGINATION ===== --}}
            @if ($newsList->hasPages())
                <div class="flex justify-center">
                    {{ $newsList->links('pagination::tailwind') }}
                </div>
            @endif

        @endif

    </div>
</div>
@endsection
