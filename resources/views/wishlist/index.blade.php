@extends('layouts.app')

@section('title', 'Wishlist — Epic Games')

@section('content')
<div class="min-h-screen bg-gray-950 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ===== PAGE HEADER ===== --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white tracking-tight">Wishlist</h1>
            <p class="text-gray-400 text-sm mt-1">
                {{ $wishlistItems->count() }} game dalam daftar keinginanmu
            </p>
        </div>

        {{-- ===== FLASH MESSAGES ===== --}}
        @foreach (['success' => 'green', 'error' => 'red', 'info' => 'blue'] as $type => $color)
            @if (session($type))
                <div class="mb-5 flex items-center gap-3 bg-{{ $color }}-900/40 border border-{{ $color }}-700/60
                            text-{{ $color }}-300 text-sm rounded-xl px-4 py-3">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10A8 8 0 112 10a8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session($type) }}</span>
                </div>
            @endif
        @endforeach

        {{-- ===== EMPTY STATE ===== --}}
        @if ($wishlistItems->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <div class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-white mb-2">Wishlist-mu masih kosong</h2>
                <p class="text-gray-400 text-sm mb-8 max-w-xs">
                    Simpan game yang ingin kamu beli nanti dengan menambahkannya ke wishlist.
                </p>
                <a href="{{ route('store') }}"
                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500
                          text-white font-semibold text-sm px-6 py-3 rounded-xl transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Jelajahi Store
                </a>
            </div>

        {{-- ===== WISHLIST ITEMS ===== --}}
        @else
            <div class="space-y-3">
                @foreach ($wishlistItems as $item)
                    @php $game = $item->game; @endphp
                    @if ($game)
                        <div class="group flex items-center gap-4 bg-gray-900 border border-gray-800 rounded-xl p-4
                                    hover:border-gray-700 transition-all duration-200">

                            {{-- Cover --}}
                            <a href="{{ route('game.show', $game->game_id) }}" class="flex-shrink-0">
                                <div class="w-24 h-14 rounded-lg overflow-hidden bg-gray-800">
                                    @if ($game->cover_image_url)
                                        <img src="{{ $game->cover_image_url }}"
                                             alt="{{ $game->title }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </a>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('game.show', $game->game_id) }}"
                                   class="text-white font-semibold text-sm hover:text-blue-400 transition-colors truncate block">
                                    {{ $game->title }}
                                </a>

                                @if ($game->publisher)
                                    <p class="text-gray-500 text-xs mt-0.5">{{ $game->publisher->name }}</p>
                                @endif

                                {{-- Platform --}}
                                @if ($game->platforms && $game->platforms->count())
                                    <div class="flex items-center gap-1.5 mt-1.5">
                                        @foreach ($game->platforms->take(3) as $platform)
                                            <span class="text-xs text-gray-600 bg-gray-800 border border-gray-700 px-1.5 py-0.5 rounded">
                                                {{ $platform->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Added date --}}
                                <p class="text-gray-700 text-xs mt-1.5">
                                    Ditambahkan {{ $item->added_at ? $item->added_at->diffForHumans() : '-' }}
                                </p>
                            </div>

                            {{-- Harga + Actions --}}
                            <div class="flex-shrink-0 flex flex-col items-end gap-2">
                                {{-- Harga --}}
                                <div class="text-right">
                                    @if ($game->discount_pct > 0)
                                        <div class="flex items-center gap-1.5 justify-end mb-0.5">
                                            <span class="bg-green-600 text-white text-xs font-bold px-1.5 py-0.5 rounded">
                                                -{{ $game->discount_pct }}%
                                            </span>
                                            <span class="text-gray-500 text-xs line-through">
                                                ${{ number_format($game->base_price, 2) }}
                                            </span>
                                        </div>
                                    @endif
                                    <span class="font-bold text-sm {{ $game->final_price == 0 ? 'text-green-400' : 'text-white' }}">
                                        @if ($game->final_price == 0) Gratis
                                        @else ${{ number_format($game->final_price, 2) }}
                                        @endif
                                    </span>
                                </div>

                                {{-- Action buttons --}}
                                <div class="flex items-center gap-2">
                                    {{-- Tambah ke Cart --}}
                                    @auth
                                        @if (! Auth::user()->alreadyOwns($game->game_id))
                                            <form action="{{ route('cart.add') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="game_id" value="{{ $game->game_id }}">
                                                <button type="submit"
                                                        class="flex items-center gap-1.5 bg-blue-600 hover:bg-blue-500
                                                               text-white text-xs font-semibold px-3 py-1.5 rounded-lg
                                                               transition-colors duration-200">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                    </svg>
                                                    Add to Cart
                                                </button>
                                            </form>
                                        @else
                                            <span class="flex items-center gap-1.5 bg-green-900/40 border border-green-700/60
                                                         text-green-400 text-xs font-semibold px-3 py-1.5 rounded-lg">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Dimiliki
                                            </span>
                                        @endif
                                    @endauth

                                    {{-- Hapus dari wishlist --}}
                                    <form action="{{ route('wishlist.remove', $item->wishlist_id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-1.5 text-gray-600 hover:text-red-400 hover:bg-red-900/20
                                                       rounded-lg transition-all duration-200"
                                                title="Hapus dari wishlist">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    @endif
                @endforeach
            </div>

            {{-- CTA lanjut belanja --}}
            <div class="mt-8 text-center">
                <a href="{{ route('store') }}"
                   class="inline-flex items-center gap-2 text-gray-400 hover:text-white text-sm transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Temukan lebih banyak game di Store →
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
