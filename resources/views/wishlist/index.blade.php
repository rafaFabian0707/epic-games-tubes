@extends('layouts.app')

@section('title', 'My Wishlist — Epic Games')

@section('content')
<div class="min-h-screen bg-[#121212] py-10">
    <div class="max-w-7xl mx-auto px-10">

        {{-- ===== PAGE HEADER ===== --}}
        <div class="flex items-start justify-between mb-6 flex-wrap gap-4">
            <h1 class="text-4xl font-bold text-white">My Wishlist</h1>

            {{-- Epic Rewards (statis, no logic) --}}
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

        {{-- ===== NOTIFICATION BANNER (statis) ===== --}}
        <div class="flex items-center justify-between bg-[#1A1A22] border-l-4 border-[#26bbff] rounded-r-xl px-5 py-4 mb-8">
            <div class="flex items-center gap-3 text-sm text-gray-300">
                <svg class="w-5 h-5 text-[#26bbff] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Get notified when your wishlisted games go on sale, or are available for purchase or pre-purchase.
            </div>
            {{-- Toggle statis --}}
            <div class="w-12 h-6 bg-gray-600 rounded-full flex-shrink-0 relative cursor-pointer">
                <div class="absolute right-1 top-1 w-4 h-4 bg-gray-400 rounded-full"></div>
            </div>
        </div>

        {{-- ===== FLASH MESSAGES ===== --}}
        @if (session('success'))
            <div class="mb-4 flex items-center gap-2 bg-green-900/40 border border-green-700/60 text-green-300 px-4 py-3 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-4 flex items-center gap-2 bg-red-900/40 border border-red-700/60 text-red-300 px-4 py-3 rounded-xl text-sm">
                {{ session('error') }}
            </div>
        @endif
        @if (session('info'))
            <div class="mb-4 flex items-center gap-2 bg-blue-900/40 border border-blue-700/60 text-blue-300 px-4 py-3 rounded-xl text-sm">
                {{ session('info') }}
            </div>
        @endif

        @if ($wishlistItems->isEmpty())
            {{-- ===== EMPTY STATE ===== --}}
            <div class="flex flex-col items-center justify-center py-32 text-center">
                <div class="w-20 h-20 bg-[#1A1A22] rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-white mb-2">Your wishlist is empty</h2>
                <p class="text-gray-400 text-sm mb-8 max-w-xs">
                    Find games you love and add them to your wishlist.
                </p>
                <a href="{{ route('store') }}"
                   class="bg-[#26bbff] hover:bg-[#56cbff] text-black font-bold text-sm px-8 py-3 rounded-lg transition-colors duration-200">
                    Browse Store
                </a>
            </div>

        @else
            <div class="flex items-start gap-8">

                {{-- ===== KIRI: Item List ===== --}}
                <div class="flex-1 space-y-0">

                    {{-- Sort bar --}}
                    <div class="flex items-center gap-4 mb-4" x-data="{ sortOpen: false }">
                        <span class="text-sm text-gray-400">Sort by:</span>
                        <div class="relative">
                            <button @click="sortOpen = !sortOpen"
                                    class="flex items-center gap-2 text-sm text-white hover:text-gray-300 transition-colors">
                                On Sale
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Items --}}
                    @foreach ($wishlistItems as $item)
                        @php $game = $item->game; @endphp
                        @if ($game)
                        <div class="bg-[#1A1A22] border border-white/5 rounded-xl mb-3 overflow-hidden
                                    hover:border-white/10 transition-colors duration-200">
                            <div class="flex items-stretch gap-0">

                                {{-- Cover --}}
                                <a href="{{ route('game.show', $game->game_id) }}"
                                   class="flex-shrink-0 w-36 h-44 overflow-hidden">
                                    @if ($game->cover_image_url)
                                        <img src="{{ $game->cover_image_url }}"
                                             alt="{{ $game->title }}"
                                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                    @else
                                        <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </a>

                                {{-- Info --}}
                                <div class="flex-1 p-5 flex flex-col justify-between min-w-0">
                                    <div>
                                        {{-- Tags + Harga --}}
                                        <div class="flex items-start justify-between gap-4 mb-2">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                @if ($game->game_type)
                                                    <span class="text-xs border border-gray-600 text-gray-300 px-2 py-0.5 rounded">
                                                        {{ ucwords(str_replace('_', ' ', $game->game_type)) }}
                                                    </span>
                                                @endif
                                                @if ($game->info_label)
                                                    <span class="text-xs border border-gray-600 text-gray-300 px-2 py-0.5 rounded">
                                                        {{ $game->info_label }}
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- Harga --}}
                                            <div class="text-right flex-shrink-0">
                                                @if ($game->discount_pct > 0)
                                                    <div class="flex items-center gap-2 justify-end">
                                                        <span class="bg-[#26bbff] text-black text-xs font-bold px-2 py-0.5 rounded">
                                                            -{{ $game->discount_pct }}%
                                                        </span>
                                                        <s class="text-gray-500 text-sm">Rp {{ number_format($game->base_price, 0, ',', '.') }}</s>
                                                        <span class="text-white font-bold">Rp {{ number_format($game->final_price, 0, ',', '.') }}</span>
                                                    </div>
                                                    @if ($game->discount_ends_at ?? false)
                                                        <p class="text-gray-500 text-xs mt-0.5 text-right">
                                                            Sale ends {{ \Carbon\Carbon::parse($game->discount_ends_at)->format('n/j/Y') }}
                                                        </p>
                                                    @endif
                                                @elseif ($game->base_price == 0)
                                                    <span class="text-green-400 font-bold">Free</span>
                                                @else
                                                    <span class="text-white font-bold">Rp {{ number_format($game->base_price, 0, ',', '.') }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Judul --}}
                                        <a href="{{ route('game.show', $game->game_id) }}"
                                           class="text-white font-semibold text-base hover:text-[#26bbff] transition-colors">
                                            {{ $game->title }}
                                        </a>

                                        {{-- Age Rating --}}
                                        @if ($game->ageRating)
                                        <div class="mt-3 bg-[#0d0d14] border border-white/5 rounded-lg px-3 py-2 flex items-center gap-3 w-fit">
                                            <div class="w-10 h-10 border-2 border-gray-400 rounded flex items-center justify-center flex-shrink-0">
                                                <span class="text-white font-black text-xs leading-none">{{ $game->ageRating->rating_label ?? '?' }}</span>
                                            </div>
                                            <span class="text-gray-400 text-xs">{{ $game->ageRating->description ?? 'General' }}</span>
                                        </div>
                                        @endif

                                        {{-- Platform icons --}}
                                        @if ($game->platforms && $game->platforms->count())
                                        <div class="flex items-center gap-2 mt-3">
                                            @foreach ($game->platforms->take(3) as $pl)
                                                <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <rect width="24" height="24" rx="4" fill="currentColor" opacity="0.2"/>
                                                    <text x="12" y="16" text-anchor="middle" font-size="8" fill="currentColor">{{ substr($pl->platform ?? $pl->name, 0, 3) }}</text>
                                                </svg>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>

                                    {{-- Action buttons --}}
                                    <div class="flex items-center gap-3 mt-4">
                                        {{-- Remove --}}
                                        <form action="{{ route('wishlist.remove', $item->wishlist_id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-sm text-gray-400 hover:text-white transition-colors px-4 py-2 rounded-lg hover:bg-white/5">
                                                Remove
                                            </button>
                                        </form>

                                        {{-- Add to Cart --}}
                                        @if (!auth()->user()->alreadyOwns($game->game_id))
                                            <form action="{{ route('cart.add') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="game_id" value="{{ $game->game_id }}">
                                                <button type="submit"
                                                        class="bg-[#26bbff] hover:bg-[#56cbff] text-black font-bold text-sm px-6 py-2 rounded-lg transition-colors duration-200">
                                                    Add To Cart
                                                </button>
                                            </form>
                                        @else
                                            <span class="bg-green-600/20 border border-green-600/40 text-green-400 text-sm font-semibold px-6 py-2 rounded-lg">
                                                ✓ In Library
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>

                {{-- ===== KANAN: Filter Sidebar ===== --}}
                <div class="w-64 flex-shrink-0 sticky top-20" x-data="{ genreOpen: true, platformOpen: true }">
                    <div class="bg-[#1A1A22] border border-white/5 rounded-xl p-5">
                        <h3 class="text-white font-bold text-sm mb-5">Filters</h3>

                        <div class="space-y-1">
                            {{-- Genre --}}
                            <div class="border-b border-white/5 pb-3">
                                <button @click="genreOpen = !genreOpen"
                                        class="flex justify-between items-center w-full py-2 text-sm text-gray-300 hover:text-white transition-colors">
                                    <span>Genre</span>
                                    <svg class="w-4 h-4 transition-transform" :class="genreOpen ? 'rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <div x-show="genreOpen" x-transition class="mt-2 text-xs text-gray-500 italic">
                                    Filter genre tersedia di halaman Browse.
                                </div>
                            </div>

                            {{-- Features --}}
                            <div class="border-b border-white/5 pb-3">
                                <button class="flex justify-between items-center w-full py-2 text-sm text-gray-300 hover:text-white transition-colors">
                                    <span>Features</span>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>

                            {{-- Platform --}}
                            <div class="pb-3">
                                <button @click="platformOpen = !platformOpen"
                                        class="flex justify-between items-center w-full py-2 text-sm text-gray-300 hover:text-white transition-colors">
                                    <span>Platform</span>
                                    <svg class="w-4 h-4 transition-transform" :class="platformOpen ? 'rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <div x-show="platformOpen" x-transition class="mt-2 text-xs text-gray-500 italic">
                                    Filter platform tersedia di halaman Browse.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif

    </div>
</div>
@endsection