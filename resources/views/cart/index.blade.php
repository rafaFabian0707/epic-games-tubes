@extends('layouts.app')

@section('title', 'My Cart — Epic Games')

@section('content')
<div class="min-h-screen bg-[#121212] py-10">
    <div class="max-w-7xl mx-auto px-10">

        {{-- ===== PAGE HEADER ===== --}}
        <div class="flex items-start justify-between mb-8 flex-wrap gap-4">
            <h1 class="text-4xl font-bold text-white">My Cart</h1>
            <div class="flex items-center gap-3">
                <span class="text-gray-400 text-sm flex items-center gap-1.5">
                    Epic Rewards
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </span>
                <span class="border border-gray-600 text-white text-sm font-semibold px-4 py-1.5 rounded-full">
                    IDR 0.00
                </span>
            </div>
        </div>

        {{-- ===== FLASH MESSAGES ===== --}}
        @if (session('error'))
            <div class="mb-5 flex items-center gap-2 bg-red-900/40 border border-red-700/60 text-red-300 px-4 py-3 rounded-xl text-sm">
                {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="mb-5 flex items-center gap-2 bg-green-900/40 border border-green-700/60 text-green-300 px-4 py-3 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if (session('info'))
            <div class="mb-5 flex items-center gap-2 bg-blue-900/40 border border-blue-700/60 text-blue-300 px-4 py-3 rounded-xl text-sm">
                {{ session('info') }}
            </div>
        @endif

        @if ($cartGames->isEmpty())
            {{-- ===== EMPTY STATE ===== --}}
            <div class="flex flex-col items-center justify-center py-32 text-center">
                <div class="w-20 h-20 bg-[#1A1A22] rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-white mb-2">Your cart is empty</h2>
                <p class="text-gray-400 text-sm mb-8 max-w-xs">
                    Looks like you haven't added anything to your cart yet.
                </p>
                <a href="{{ route('store') }}"
                   class="bg-[#26bbff] hover:bg-[#56cbff] text-black font-bold text-sm px-8 py-3 rounded-lg transition-colors duration-200">
                    Browse Store
                </a>
            </div>

        @else
            <div class="flex items-start gap-8">

                {{-- ===== KIRI: Item List ===== --}}
                <div class="flex-1 space-y-3">
                    @foreach ($cartGames as $game)
                    <div class="bg-[#1A1A22] border border-white/5 rounded-xl overflow-hidden
                                hover:border-white/10 transition-colors duration-200">
                        <div class="flex items-stretch">

                            {{-- Cover --}}
                            <a href="{{ route('game.show', $game->game_id) }}"
                               class="flex-shrink-0 w-32 h-40 overflow-hidden">
                                @if ($game->cover_image_url)
                                    <img src="{{ $game->cover_image_url }}"
                                         alt="{{ $game->title }}"
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </a>

                            {{-- Info --}}
                            <div class="flex-1 px-6 py-4 flex flex-col justify-between min-w-0">
                                <div>
                                    {{-- Harga kanan atas --}}
                                    <div class="flex items-start justify-between gap-4 mb-1">
                                        {{-- Type badge --}}
                                        @if ($game->game_type)
                                            <span class="text-xs border border-gray-600 text-gray-300 px-2 py-0.5 rounded">
                                                {{ ucwords(str_replace('_', ' ', $game->game_type)) }}
                                            </span>
                                        @endif

                                        {{-- Harga --}}
                                        <div class="text-right flex-shrink-0">
                                            @if ($game->discount_pct > 0)
                                                <div class="flex items-center gap-2 justify-end">
                                                    <s class="text-gray-500 text-sm">Rp {{ number_format($game->base_price, 0, ',', '.') }}</s>
                                                    <span class="text-white font-bold">Rp {{ number_format($game->final_price, 0, ',', '.') }}</span>
                                                </div>
                                            @elseif ($game->base_price == 0)
                                                <span class="text-green-400 font-bold">Free</span>
                                            @else
                                                <span class="text-white font-bold">Rp {{ number_format($game->base_price, 0, ',', '.') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Judul --}}
                                    <a href="{{ route('game.show', $game->game_id) }}"
                                       class="text-white font-semibold text-base hover:text-[#26bbff] transition-colors block mb-3">
                                        {{ $game->title }}
                                    </a>

                                    {{-- Age Rating --}}
                                    @if ($game->ageRating)
                                    <div class="bg-[#0d0d14] border border-white/5 rounded-lg px-3 py-2 flex items-center gap-3 w-fit mb-2">
                                        <div class="w-9 h-9 border-2 border-gray-400 rounded flex items-center justify-center flex-shrink-0">
                                            <span class="text-white font-black text-xs">{{ $game->ageRating->rating_label ?? '?' }}</span>
                                        </div>
                                        <span class="text-gray-400 text-xs">{{ $game->ageRating->description ?? 'General' }}</span>
                                    </div>
                                    @endif

                                    {{-- Epic Rewards statis --}}
                                    <p class="text-[#f5a623] text-xs flex items-center gap-1.5 mt-2">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                        Earn 5% back in Epic Rewards
                                    </p>
                                </div>

                                {{-- Actions --}}
                                <div class="flex items-center gap-3 mt-4 pt-3 border-t border-white/5">
                                    {{-- Remove --}}
                                    <form action="{{ route('cart.remove', $game->game_id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-sm text-gray-400 hover:text-white transition-colors px-3 py-1.5 rounded-lg hover:bg-white/5">
                                            Remove
                                        </button>
                                    </form>

                                    {{-- Move to Wishlist --}}
                                    <form action="{{ route('wishlist.toggle') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="game_id" value="{{ $game->game_id }}">
                                        <button type="submit"
                                                class="text-sm text-gray-400 hover:text-white border border-gray-600 hover:border-gray-400
                                                       transition-colors px-4 py-1.5 rounded-lg">
                                            Move to wishlist
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- ===== KANAN: Summary ===== --}}
                <div class="w-80 flex-shrink-0 sticky top-20">
                    <div class="bg-[#1A1A22] border border-white/5 rounded-xl p-6">
                        <h2 class="text-white font-bold text-xl mb-6 leading-tight">
                            Games and Apps<br>Summary
                        </h2>

                        {{-- Price breakdown --}}
                        <div class="space-y-3 mb-5">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Price</span>
                                <span class="text-white">Rp {{ number_format($cartGames->sum('base_price'), 0, ',', '.') }}</span>
                            </div>
                            @if ($cartGames->sum('discount_pct') > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Sale Discount</span>
                                <span class="text-green-400">
                                    -Rp {{ number_format($cartGames->sum('base_price') - $cartGames->sum('final_price'), 0, ',', '.') }}
                                </span>
                            </div>
                            @endif
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Taxes</span>
                                <span class="text-gray-400">Calculated at Checkout</span>
                            </div>
                        </div>

                        <div class="border-t border-white/10 my-4"></div>

                        {{-- Subtotal --}}
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-white font-bold">Subtotal</span>
                            <span class="text-white font-bold text-lg">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>

                        {{-- Check Out button --}}
                        <a href="{{ route('checkout.index') }}"
                           class="w-full block text-center bg-[#26bbff] hover:bg-[#56cbff] text-black font-bold
                                  py-3.5 rounded-lg transition-colors duration-200 text-base">
                            Check Out
                        </a>
                    </div>
                </div>

            </div>
        @endif

    </div>
</div>
@endsection