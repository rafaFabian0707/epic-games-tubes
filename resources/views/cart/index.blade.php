@extends('layouts.app')

@section('title', 'Keranjang Belanja — Epic Games')

@section('content')
<div class="min-h-screen bg-gray-950 py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ===== PAGE HEADER ===== --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-white tracking-tight">Keranjang Belanja</h1>
            <p class="text-gray-400 text-sm mt-1">
                {{ $cartGames->count() }} item dalam keranjangmu
            </p>
        </div>

        {{-- ===== FLASH MESSAGES ===== --}}
        @if (session('error'))
            <div class="mb-6 flex items-start gap-3 bg-red-900/40 border border-red-700/60 text-red-300 text-sm rounded-xl px-4 py-3">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10A8 8 0 11 2 10a8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 flex items-center gap-3 bg-green-900/40 border border-green-700/60 text-green-300 text-sm rounded-xl px-4 py-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('info'))
            <div class="mb-6 flex items-center gap-3 bg-blue-900/40 border border-blue-700/60 text-blue-300 text-sm rounded-xl px-4 py-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10A8 8 0 112 10a8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        {{-- ===== CART EMPTY STATE ===== --}}
        @if ($cartGames->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <div class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-white mb-2">Keranjangmu masih kosong</h2>
                <p class="text-gray-400 text-sm mb-8 max-w-xs">
                    Temukan game favoritmu dan tambahkan ke keranjang.
                </p>
                <a href="{{ route('store') }}"
                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-semibold text-sm px-6 py-3 rounded-xl transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Jelajahi Game
                </a>
            </div>

        {{-- ===== CART HAS ITEMS ===== --}}
        @else
            <div class="flex flex-col lg:flex-row gap-8">

                {{-- ===== ITEM LIST (kiri) ===== --}}
                <div class="flex-1 space-y-3">
                    @foreach ($cartGames as $game)
                        <div class="group flex items-center gap-4 bg-gray-900 border border-gray-800 rounded-xl p-4
                                    hover:border-gray-700 transition-all duration-200">

                            {{-- Thumbnail --}}
                            <a href="{{ route('game.show', $game->game_id) }}" class="flex-shrink-0">
                                <div class="w-20 h-12 rounded-lg overflow-hidden bg-gray-800">
                                    @if ($game->cover_image_url)
                                        <img src="{{ $game->cover_image_url }}"
                                             alt="{{ $game->title }}"
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
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

                                {{-- Badge type --}}
                                @if ($game->game_type !== 'base_game')
                                    <span class="inline-block mt-1 text-xs bg-gray-700 text-gray-300 px-2 py-0.5 rounded-full">
                                        {{ ucwords(str_replace('_', ' ', $game->game_type)) }}
                                    </span>
                                @endif
                            </div>

                            {{-- Harga --}}
                            <div class="flex-shrink-0 text-right">
                                @if ($game->discount_pct > 0)
                                    <div class="flex items-center gap-2 justify-end mb-0.5">
                                        <span class="bg-green-600 text-white text-xs font-bold px-1.5 py-0.5 rounded">
                                            -{{ $game->discount_pct }}%
                                        </span>
                                        <span class="text-gray-500 text-xs line-through">
                                            ${{ number_format($game->base_price, 2) }}
                                        </span>
                                    </div>
                                @endif

                                <span class="text-white font-bold text-sm">
                                    @if ($game->final_price == 0)
                                        <span class="text-green-400">Gratis</span>
                                    @else
                                        ${{ number_format($game->final_price, 2) }}
                                    @endif
                                </span>
                            </div>

                            {{-- Hapus --}}
                            <form action="{{ route('cart.remove', $game->game_id) }}" method="POST" class="flex-shrink-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="p-2 text-gray-600 hover:text-red-400 hover:bg-red-900/20 rounded-lg transition-all duration-200"
                                        title="Hapus dari keranjang">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                {{-- ===== ORDER SUMMARY (kanan) ===== --}}
                <div class="lg:w-80 flex-shrink-0">
                    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 sticky top-20">
                        <h2 class="text-white font-bold text-base mb-5">Ringkasan Pesanan</h2>

                        {{-- Daftar item dengan harga --}}
                        <div class="space-y-3 mb-5">
                            @foreach ($cartGames as $game)
                                <div class="flex items-start justify-between gap-3 text-sm">
                                    <span class="text-gray-400 leading-tight flex-1 min-w-0 truncate">
                                        {{ $game->title }}
                                    </span>
                                    <span class="text-white font-medium flex-shrink-0">
                                        @if ($game->final_price == 0)
                                            <span class="text-green-400">Gratis</span>
                                        @else
                                            ${{ number_format($game->final_price, 2) }}
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        {{-- Divider --}}
                        <div class="border-t border-gray-800 my-4"></div>

                        {{-- Total --}}
                        <div class="flex items-center justify-between mb-6">
                            <span class="text-gray-300 font-semibold">Total</span>
                            <span class="text-white font-bold text-xl">
                                ${{ number_format($total, 2) }}
                            </span>
                        </div>

                        {{-- Tombol Checkout --}}
                        <a href="{{ route('checkout.index') }}"
                           class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-500
                                  text-white font-bold text-sm py-3 rounded-xl transition-colors duration-200 mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Lanjut ke Checkout
                        </a>

                        {{-- Lanjut belanja --}}
                        <a href="{{ route('store') }}"
                           class="w-full flex items-center justify-center text-gray-400 hover:text-white text-sm py-2 transition-colors duration-200">
                            ← Lanjut Belanja
                        </a>

                        {{-- Catatan --}}
                        <p class="text-gray-600 text-xs text-center mt-4 leading-relaxed">
                            Harga bisa berubah sewaktu-waktu. Diskon diverifikasi saat checkout.
                        </p>
                    </div>
                </div>

            </div>
        @endif

    </div>
</div>
@endsection
