@extends('layouts.app')

@section('title', $keyword ? "Hasil Pencarian: {$keyword} — Epic Games" : 'Cari Game — Epic Games')

@section('content')
<div class="min-h-screen bg-gray-950 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ===== SEARCH BAR (besar) ===== --}}
        <div class="mb-8">
            <form action="{{ route('store.search') }}" method="GET">
                <div class="relative max-w-2xl">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500 pointer-events-none"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           name="q"
                           value="{{ $keyword }}"
                           placeholder="Cari game, publisher, genre..."
                           autofocus
                           class="w-full bg-gray-900 border border-gray-700 text-white text-base rounded-xl pl-12 pr-4 py-3.5
                                  focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30
                                  placeholder-gray-500 transition-all duration-200">
                    @if ($keyword)
                        <a href="{{ route('store.search') }}"
                           class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ===== RESULT HEADER ===== --}}
        @if ($keyword)
            <div class="mb-6">
                <p class="text-gray-400 text-sm">
                    @if ($games->isNotEmpty())
                        Menampilkan <span class="text-white font-semibold">{{ $games->count() }}</span> hasil untuk
                        "<span class="text-blue-400">{{ $keyword }}</span>"
                    @else
                        Tidak ada hasil untuk "<span class="text-blue-400">{{ $keyword }}</span>"
                    @endif
                </p>
            </div>
        @endif

        {{-- ===== NO KEYWORD STATE ===== --}}
        @if (! $keyword)
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mb-5">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-white mb-2">Cari Game Favoritmu</h2>
                <p class="text-gray-500 text-sm max-w-xs">
                    Ketikkan judul game, nama publisher, atau kata kunci lainnya.
                    Minimal 2 karakter untuk memulai pencarian.
                </p>
            </div>

        {{-- ===== NO RESULTS ===== --}}
        @elseif ($games->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mb-5">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-white mb-2">Tidak Ditemukan</h2>
                <p class="text-gray-500 text-sm mb-6 max-w-xs">
                    Kami tidak menemukan game yang cocok dengan "<strong class="text-gray-300">{{ $keyword }}</strong>".
                    Coba kata kunci lain.
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('store') }}"
                       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white
                              font-semibold text-sm px-5 py-2.5 rounded-xl transition-colors duration-200">
                        Lihat Semua Game
                    </a>
                    <a href="{{ route('jelajahi') }}"
                       class="inline-flex items-center gap-2 bg-gray-800 hover:bg-gray-700 border border-gray-700
                              text-gray-300 font-semibold text-sm px-5 py-2.5 rounded-xl transition-colors duration-200">
                        Jelajahi Store
                    </a>
                </div>
            </div>

        {{-- ===== SEARCH RESULTS ===== --}}
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach ($games as $game)
                    <a href="{{ route('game.show', $game->game_id) }}"
                       class="group flex flex-col bg-gray-900 border border-gray-800 rounded-xl overflow-hidden
                              hover:border-gray-700 hover:-translate-y-1 transition-all duration-300">

                        {{-- Cover --}}
                        <div class="relative h-40 overflow-hidden bg-gray-800 flex-shrink-0">
                            @if ($game->cover_image_url)
                                <img src="{{ $game->cover_image_url }}"
                                     alt="{{ $game->title }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif

                            {{-- Discount badge --}}
                            @if ($game->discount_pct > 0)
                                <div class="absolute top-2 left-2">
                                    <span class="bg-green-600 text-white text-xs font-bold px-2 py-0.5 rounded">
                                        -{{ $game->discount_pct }}%
                                    </span>
                                </div>
                            @endif

                            {{-- Info badge (Early Access, dll) --}}
                            @if ($game->info_label)
                                <div class="absolute top-2 right-2">
                                    <span class="bg-gray-900/80 backdrop-blur-sm text-gray-300 text-xs px-2 py-0.5 rounded border border-gray-700">
                                        {{ $game->info_label }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="p-4 flex flex-col flex-1">
                            {{-- Publisher --}}
                            @if ($game->publisher)
                                <p class="text-gray-600 text-xs mb-1 truncate">{{ $game->publisher->name }}</p>
                            @endif

                            {{-- Judul --}}
                            <h3 class="text-white font-semibold text-sm leading-snug mb-3 line-clamp-2
                                       group-hover:text-blue-400 transition-colors duration-200 flex-1">
                                {{ $game->title }}
                            </h3>

                            {{-- Platform icons --}}
                            @if ($game->platforms && $game->platforms->count())
                                <div class="flex items-center gap-1 mb-3">
                                    @foreach ($game->platforms->take(3) as $platform)
                                        <span class="text-gray-600 text-xs bg-gray-800 border border-gray-700 px-1.5 py-0.5 rounded">
                                            {{ $platform->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Harga --}}
                            <div class="flex items-center gap-2 mt-auto pt-2 border-t border-gray-800">
                                @if ($game->discount_pct > 0)
                                    <span class="text-gray-500 text-xs line-through">
                                        ${{ number_format($game->base_price, 2) }}
                                    </span>
                                @endif
                                <span class="font-bold text-sm {{ $game->final_price == 0 ? 'text-green-400' : 'text-white' }}">
                                    @if ($game->final_price == 0)
                                        Gratis
                                    @else
                                        ${{ number_format($game->final_price, 2) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Saran jika hasil sedikit --}}
            @if ($games->count() < 5)
                <div class="mt-10 text-center">
                    <p class="text-gray-600 text-sm mb-3">
                        Ingin lihat lebih banyak game?
                    </p>
                    <a href="{{ route('jelajahi') }}"
                       class="inline-flex items-center gap-2 text-blue-400 hover:text-blue-300 text-sm transition-colors">
                        Jelajahi semua game di store →
                    </a>
                </div>
            @endif
        @endif

    </div>
</div>
@endsection
