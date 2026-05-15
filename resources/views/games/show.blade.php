@extends('layouts.app')

@section('title', $game->title . ' — Epic Games Store')

@push('styles')
<style>
    .hero-gradient {
        background: linear-gradient(to top, #030712 0%, #030712 20%, transparent 60%);
    }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    .media-thumb { transition: all 0.2s ease; }
    .media-thumb:hover { transform: scale(1.03); }
    .tab-active { border-bottom: 2px solid #3b82f6; color: #fff; }
    .tab-inactive { border-bottom: 2px solid transparent; color: #9ca3af; }
    .tab-inactive:hover { color: #d1d5db; }
    .fade-in { animation: fadeIn 0.4s ease forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    .req-tab-active   { background: #1e40af; color: #fff; }
    .req-tab-inactive { background: #1f2937; color: #9ca3af; }
</style>
@endpush

@section('content')

{{-- ============================
     HERO — Cover / Banner
============================= --}}
<div class="relative w-full bg-gray-950 overflow-hidden" style="min-height: 420px;">

    {{-- Background blur --}}
    @if($game->cover_image_url)
    <div class="absolute inset-0 opacity-20 blur-2xl scale-110"
         style="background-image: url('{{ $game->cover_image_url }}'); background-size: cover; background-position: center;"></div>
    @endif

    <div class="hero-gradient absolute inset-0 z-10"></div>

    <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col lg:flex-row gap-8 items-start">

            {{-- Cover image --}}
            <div class="flex-shrink-0 w-48 sm:w-56 rounded-xl overflow-hidden shadow-2xl shadow-black/60 border border-gray-700/50">
                @if($game->cover_image_url)
                    <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}" class="w-full h-auto object-cover">
                @else
                    <div class="w-full aspect-[3/4] bg-gray-800 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Hero info --}}
            <div class="flex-1 pt-2">

                {{-- Badges: info label --}}
                <div class="flex flex-wrap gap-2 mb-3">
                    @if($game->info)
                    <span class="bg-blue-600/80 backdrop-blur-sm text-white text-xs px-2.5 py-1 rounded-full font-medium">
                        {{ $game->info_label }}
                    </span>
                    @endif
                    @if($game->game_type !== 'base_game')
                    <span class="bg-purple-600/80 text-white text-xs px-2.5 py-1 rounded-full font-medium capitalize">
                        {{ str_replace('_', ' ', $game->game_type) }}
                    </span>
                    @endif
                </div>

                {{-- Title --}}
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-2 leading-tight">
                    {{ $game->title }}
                </h1>

                {{-- Developer / Publisher --}}
                <p class="text-gray-400 text-sm mb-4">
                    @if($game->developer)
                        <span class="text-gray-300">{{ $game->developer->name }}</span>
                    @endif
                    @if($game->developer && $game->publisher)
                        <span class="text-gray-600 mx-1">·</span>
                    @endif
                    @if($game->publisher)
                        <span>{{ $game->publisher->name }}</span>
                    @endif
                </p>

                {{-- Genres --}}
                @if($game->genres->count())
                <div class="flex flex-wrap gap-1.5 mb-4">
                    @foreach($game->genres as $genre)
                    <span class="bg-gray-800 text-gray-300 text-xs px-2.5 py-1 rounded-full border border-gray-700 hover:border-gray-500 transition-colors">
                        {{ $genre->name }}
                    </span>
                    @endforeach
                </div>
                @endif

                {{-- Tags --}}
                @if($game->tags->count())
                <div class="flex flex-wrap gap-1.5 mb-5">
                    @foreach($game->tags as $tag)
                    <span class="bg-gray-800/60 text-gray-400 text-xs px-2 py-0.5 rounded border border-gray-700/50">
                        {{ $tag->emoji }} {{ $tag->label }}
                    </span>
                    @endforeach
                </div>
                @endif

                {{-- Rating --}}
                @if($game->avg_rating)
                <div class="flex items-center gap-2 mb-5">
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= round($game->avg_rating / 2) ? 'text-yellow-400' : 'text-gray-600' }}"
                             fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        @endfor
                    </div>
                    <span class="text-white font-semibold text-sm">{{ number_format($game->avg_rating, 1) }}</span>
                    <span class="text-gray-500 text-xs">/ 10</span>
                </div>
                @endif

                {{-- Platforms --}}
                @if($game->platforms->count())
                <div class="flex items-center gap-2 mb-5">
                    <span class="text-gray-500 text-xs uppercase tracking-wider">Platform:</span>
                    @foreach($game->platforms as $platform)
                    <span class="text-gray-300 text-xs bg-gray-800 px-2 py-0.5 rounded border border-gray-700">
                        {{ $platform->platform }}
                    </span>
                    @endforeach
                </div>
                @endif

                {{-- Release date / Age rating --}}
                <div class="flex flex-wrap gap-4 text-xs text-gray-500 mb-6">
                    @if($game->release_date)
                    <span>📅 Rilis: <span class="text-gray-300">{{ $game->release_date->format('d M Y') }}</span></span>
                    @endif
                    @if($game->ageRating)
                    <span>🔞 Rating: <span class="text-gray-300">{{ $game->ageRating->age }}</span></span>
                    @endif
                    @if($game->refund_type)
                    <span>↩️ Refund: <span class="text-gray-300">{{ $game->refund_type }}</span></span>
                    @endif
                </div>

            </div>

            {{-- ===== BUY PANEL ===== --}}
            <div class="flex-shrink-0 w-full lg:w-72 bg-gray-900/80 backdrop-blur-sm border border-gray-700/50 rounded-2xl p-5 shadow-2xl">

                {{-- Price --}}
                <div class="mb-4">
                    @if($game->final_price == 0)
                        <div class="text-2xl font-extrabold text-green-400">GRATIS</div>
                    @elseif($game->discount_pct > 0)
                        <div class="flex items-center gap-3 mb-1">
                            <span class="bg-green-600 text-white text-sm font-bold px-2 py-0.5 rounded">
                                -{{ $game->discount_pct }}%
                            </span>
                        </div>
                        <div class="text-gray-500 text-sm line-through">
                            Rp {{ number_format($game->base_price, 0, ',', '.') }}
                        </div>
                        <div class="text-2xl font-extrabold text-white">
                            Rp {{ number_format($game->final_price, 0, ',', '.') }}
                        </div>
                    @else
                        <div class="text-2xl font-extrabold text-white">
                            Rp {{ number_format($game->base_price, 0, ',', '.') }}
                        </div>
                    @endif
                </div>

                {{-- CTA Buttons --}}
                @auth
                    <form action="{{ route('cart.add') }}" method="POST" class="mb-2">
                        @csrf
                        <input type="hidden" name="game_id" value="{{ $game->game_id }}">
                        <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2.5 px-4 rounded-xl transition-all duration-200 hover:shadow-lg hover:shadow-blue-500/20 active:scale-95">
                            {{ $game->final_price == 0 ? 'Ambil Sekarang' : 'Tambah ke Keranjang' }}
                        </button>
                    </form>
                    <form action="{{ route('wishlist.toggle') }}" method="POST">
                        @csrf
                        <input type="hidden" name="game_id" value="{{ $game->game_id }}">
                        <button type="submit"
                                class="w-full bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white font-medium py-2.5 px-4 rounded-xl transition-all duration-200 border border-gray-700 hover:border-gray-500 flex items-center justify-center gap-2 active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            Tambah ke Wishlist
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="block w-full text-center bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2.5 px-4 rounded-xl transition-all duration-200 hover:shadow-lg hover:shadow-blue-500/20 mb-2">
                        Login untuk Membeli
                    </a>
                    <a href="{{ route('register') }}"
                       class="block w-full text-center bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white font-medium py-2.5 px-4 rounded-xl transition-all duration-200 border border-gray-700 hover:border-gray-500">
                        Daftar Akun
                    </a>
                @endauth

                {{-- Social links --}}
                @if($game->socialLinks->count())
                <div class="mt-4 pt-4 border-t border-gray-700/50 flex items-center gap-3">
                    @foreach($game->socialLinks as $link)
                    <a href="{{ $link->url }}" target="_blank" rel="noopener"
                       class="text-gray-500 hover:text-white transition-colors text-xs">
                        {{ $link->platform ?? 'Link' }}
                    </a>
                    @endforeach
                </div>
                @endif

            </div>

        </div>
    </div>
</div>

{{-- ============================
     MAIN CONTENT
============================= --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col lg:flex-row gap-8">

        {{-- ===== LEFT — Main column ===== --}}
        <div class="flex-1 min-w-0 space-y-8">

            {{-- TABS navigation --}}
            <div x-data="{ tab: 'overview' }" class="fade-in">
                <div class="flex border-b border-gray-800 mb-6 gap-1 overflow-x-auto scrollbar-hide">
                    <button @click="tab = 'overview'"
                            :class="tab === 'overview' ? 'tab-active' : 'tab-inactive'"
                            class="px-4 py-2.5 text-sm font-medium whitespace-nowrap transition-colors">
                        Overview
                    </button>
                    @if($game->achievements->count())
                    <button @click="tab = 'achievements'"
                            :class="tab === 'achievements' ? 'tab-active' : 'tab-inactive'"
                            class="px-4 py-2.5 text-sm font-medium whitespace-nowrap transition-colors">
                        Achievements
                        <span class="ml-1 text-xs bg-gray-700 text-gray-300 px-1.5 py-0.5 rounded-full">{{ $game->achievements->count() }}</span>
                    </button>
                    @endif
                    @if($game->criticReviews->count())
                    <button @click="tab = 'reviews'"
                            :class="tab === 'reviews' ? 'tab-active' : 'tab-inactive'"
                            class="px-4 py-2.5 text-sm font-medium whitespace-nowrap transition-colors">
                        Critic Reviews
                    </button>
                    @endif
                    @if($game->systemRequirements)
                    <button @click="tab = 'system'"
                            :class="tab === 'system' ? 'tab-active' : 'tab-inactive'"
                            class="px-4 py-2.5 text-sm font-medium whitespace-nowrap transition-colors">
                        Spesifikasi
                    </button>
                    @endif
                </div>

                {{-- TAB: Overview --}}
                <div x-show="tab === 'overview'" x-transition:enter="fade-in">

                    {{-- Announcement / short desc --}}
                    @if($game->announce)
                    <div class="bg-blue-950/30 border border-blue-800/30 rounded-xl p-4 mb-6">
                        <p class="text-blue-200 text-sm leading-relaxed italic">{{ $game->announce }}</p>
                    </div>
                    @endif

                    {{-- Main description --}}
                    @if($game->main_desc)
                    <div class="prose prose-invert prose-sm max-w-none mb-6">
                        <p class="text-gray-300 leading-relaxed text-sm">{{ $game->main_desc }}</p>
                    </div>
                    @endif

                    {{-- Full description --}}
                    @if($game->desc)
                    <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5 mb-6">
                        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Deskripsi</h3>
                        <p class="text-gray-300 leading-relaxed text-sm whitespace-pre-line">{{ $game->desc }}</p>
                    </div>
                    @endif

                    {{-- Features --}}
                    @if($game->features->count())
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Fitur</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($game->features as $feature)
                            <div class="flex items-center gap-2.5 bg-gray-900/50 border border-gray-800 rounded-lg px-3 py-2.5 hover:border-gray-600 transition-colors">
                                <div class="w-1.5 h-1.5 bg-blue-500 rounded-full flex-shrink-0"></div>
                                <span class="text-gray-300 text-sm">{{ $feature->name }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- DLC / Children --}}
                    @if($game->children->count())
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-3">Add-on & DLC</h3>
                        <div class="space-y-2">
                            @foreach($game->children as $child)
                            <a href="{{ route('game.show', $child->game_id) }}"
                               class="flex items-center justify-between bg-gray-900/50 border border-gray-800 rounded-xl px-4 py-3 hover:border-gray-600 hover:bg-gray-900 transition-all group">
                                <div>
                                    <p class="text-sm font-medium text-gray-200 group-hover:text-white transition-colors">{{ $child->title }}</p>
                                    <p class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', $child->game_type) }}</p>
                                </div>
                                <div class="text-right">
                                    @if($child->final_price == 0)
                                        <span class="text-green-400 text-sm font-bold">GRATIS</span>
                                    @else
                                        <span class="text-white text-sm font-bold">Rp {{ number_format($child->final_price, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

                {{-- TAB: Achievements --}}
                @if($game->achievements->count())
                <div x-show="tab === 'achievements'" x-transition:enter="fade-in">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($game->achievements as $ach)
                        <div class="flex items-start gap-3 bg-gray-900/50 border border-gray-800 rounded-xl p-4 hover:border-gray-600 transition-colors">
                            @if($ach->icon_url)
                            <img src="{{ $ach->icon_url }}" alt="{{ $ach->title }}"
                                 class="w-12 h-12 rounded-lg object-cover flex-shrink-0 bg-gray-800">
                            @else
                            <div class="w-12 h-12 rounded-lg bg-gray-800 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                            @endif
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-200 truncate">{{ $ach->title }}</p>
                                @if($ach->desc ?? $ach->description ?? null)
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $ach->desc ?? $ach->description }}</p>
                                @endif
                                @if($ach->xp ?? null)
                                <span class="text-xs text-yellow-500 font-medium mt-1 block">{{ $ach->xp }} XP</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- TAB: Critic Reviews --}}
                @if($game->criticReviews->count())
                <div x-show="tab === 'reviews'" x-transition:enter="fade-in">
                    <div class="space-y-4">
                        @foreach($game->criticReviews as $review)
                        <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5 hover:border-gray-600 transition-colors">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <p class="font-semibold text-gray-200">{{ $review->critic ?? $review->reviewer ?? 'Reviewer' }}</p>
                                    @if($review->outlet ?? $review->source ?? null)
                                    <p class="text-xs text-gray-500">{{ $review->outlet ?? $review->source }}</p>
                                    @endif
                                </div>
                                @if($review->score ?? null)
                                <div class="bg-blue-600 text-white text-lg font-extrabold w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0">
                                    {{ $review->score }}
                                </div>
                                @endif
                            </div>
                            @if($review->quote ?? $review->body ?? $review->review ?? null)
                            <p class="text-gray-400 text-sm leading-relaxed italic">
                                "{{ $review->quote ?? $review->body ?? $review->review }}"
                            </p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- TAB: System Requirements --}}
                @if($game->systemRequirements)
                <div x-show="tab === 'system'" x-transition:enter="fade-in" x-data="{ req: 'minimum' }">
                    <div class="flex gap-2 mb-5">
                        <button @click="req = 'minimum'"
                                :class="req === 'minimum' ? 'req-tab-active' : 'req-tab-inactive'"
                                class="px-4 py-2 text-sm rounded-lg font-medium transition-colors">Minimum</button>
                        <button @click="req = 'recommended'"
                                :class="req === 'recommended' ? 'req-tab-active' : 'req-tab-inactive'"
                                class="px-4 py-2 text-sm rounded-lg font-medium transition-colors">Recommended</button>
                    </div>

                    @php $req = $game->systemRequirements; @endphp

                    <div x-show="req === 'minimum'" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @if($req->min_os)         <x-req-row label="OS"        :value="$req->min_os"/>         @endif
                        @if($req->min_cpu)        <x-req-row label="CPU"       :value="$req->min_cpu"/>        @endif
                        @if($req->min_gpu)        <x-req-row label="GPU"       :value="$req->min_gpu"/>        @endif
                        @if($req->min_ram)        <x-req-row label="RAM"       :value="$req->min_ram"/>        @endif
                        @if($req->min_storage)    <x-req-row label="Storage"   :value="$req->min_storage"/>    @endif
                        @if($req->min_directx)    <x-req-row label="DirectX"   :value="$req->min_directx"/>    @endif
                    </div>

                    <div x-show="req === 'recommended'" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @if($req->rec_os)         <x-req-row label="OS"        :value="$req->rec_os"/>         @endif
                        @if($req->rec_cpu)        <x-req-row label="CPU"       :value="$req->rec_cpu"/>        @endif
                        @if($req->rec_gpu)        <x-req-row label="GPU"       :value="$req->rec_gpu"/>        @endif
                        @if($req->rec_ram)        <x-req-row label="RAM"       :value="$req->rec_ram"/>        @endif
                        @if($req->rec_storage)    <x-req-row label="Storage"   :value="$req->rec_storage"/>    @endif
                        @if($req->rec_directx)    <x-req-row label="DirectX"   :value="$req->rec_directx"/>    @endif
                    </div>
                </div>
                @endif

            </div>
            {{-- end x-data tabs --}}

        </div>
        {{-- end left column --}}

        {{-- ===== RIGHT — Sidebar ===== --}}
        <div class="w-full lg:w-64 flex-shrink-0 space-y-6">

            {{-- Game details card --}}
            <div class="bg-gray-900/50 border border-gray-800 rounded-xl p-5">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Detail Game</h3>
                <dl class="space-y-3 text-sm">
                    @if($game->developer)
                    <div>
                        <dt class="text-gray-500 text-xs mb-0.5">Developer</dt>
                        <dd class="text-gray-200">{{ $game->developer->name }}</dd>
                    </div>
                    @endif
                    @if($game->publisher)
                    <div>
                        <dt class="text-gray-500 text-xs mb-0.5">Publisher</dt>
                        <dd class="text-gray-200">{{ $game->publisher->name }}</dd>
                    </div>
                    @endif
                    @if($game->release_date)
                    <div>
                        <dt class="text-gray-500 text-xs mb-0.5">Tanggal Rilis</dt>
                        <dd class="text-gray-200">{{ $game->release_date->format('d M Y') }}</dd>
                    </div>
                    @endif
                    @if($game->platforms->count())
                    <div>
                        <dt class="text-gray-500 text-xs mb-0.5">Platform</dt>
                        <dd class="text-gray-200">{{ $game->platforms->pluck('platform')->join(', ') }}</dd>
                    </div>
                    @endif
                    @if($game->ageRating)
                    <div>
                        <dt class="text-gray-500 text-xs mb-0.5">Age Rating</dt>
                        <dd class="text-gray-200">{{ $game->ageRating->age }}
                            @if($game->ageRating->desc)
                            <span class="text-gray-500 text-xs"> — {{ $game->ageRating->desc }}</span>
                            @endif
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- Discounts info --}}
            @php
                $activeDisc = $game->discounts->filter(fn($d) =>
                    $d->is_active && $d->start_date <= now() && $d->end_date >= now()
                )->first();
            @endphp
            @if($activeDisc)
            <div class="bg-green-950/40 border border-green-800/40 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-green-400 text-sm font-bold">🏷️ Diskon Aktif</span>
                </div>
                <p class="text-green-300 text-2xl font-extrabold">-{{ $activeDisc->discount_pct }}%</p>
                <p class="text-gray-400 text-xs mt-1">
                    Berlaku hingga {{ \Carbon\Carbon::parse($activeDisc->end_date)->format('d M Y') }}
                </p>
            </div>
            @endif

        </div>
        {{-- end right sidebar --}}

    </div>

    {{-- ============================
         RELATED GAMES
    ============================= --}}
    @if($relatedGames->count())
    <div class="mt-12 fade-in">
        <h2 class="text-xl font-bold mb-5 flex items-center gap-2">
            Game Serupa
            <span class="text-gray-600 text-sm font-normal">berdasarkan genre</span>
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($relatedGames as $related)
            @include('components.game-card', ['game' => $related])
            @endforeach
        </div>
    </div>
    @endif

</div>
{{-- end max-w-7xl --}}

@endsection