@extends('layouts.app')
@section('title', $game->title . ' — Epic Games Store')

@push('styles')
<style>
    .tab-link-active   { color:#fff; border-bottom:2px solid #3b82f6; }
    .tab-link-inactive { color:#9ca3af; border-bottom:2px solid transparent; }
    .tab-link-inactive:hover { color:#e5e7eb; }
    .fade-in { animation: fadeIn .35s ease forwards; }
    @@keyframes fadeIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:translateY(0)} }
    .line-clamp-6 { display:-webkit-box; -webkit-line-clamp:6; -webkit-box-orient:vertical; overflow:hidden; }
    /* Donut chart */
    .donut-ring { transform-origin:center; transform:rotate(-90deg); }
</style>
@endpush

@section('content')
@php
    /* Diskon aktif */
    $activeDisc = $game->discounts->filter(fn($d) =>
        $d->is_active && $d->start_date <= now() && $d->end_date >= now()
    )->first();

    /* Critic summary (dari baris pertama) */
    $firstReview = $game->criticReviews->first();

    /* System req */
    $req = $game->systemRequirements;

    /* Stars (skala 5) */
    $rating    = (float) $game->avg_rating;
    $fullStars = (int) floor($rating);
    $halfStar  = ($rating - $fullStars) >= 0.25;
    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

    /* Social icon map */
    $socialIcons = [
        'discord'   => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/></svg>',
        'facebook'  => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
        'twitter'   => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
        'x'         => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
        'youtube'   => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
        'twitch'    => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M11.571 4.714h1.715v5.143H11.57zm4.715 0H18v5.143h-1.714zM6 0L1.714 4.286v15.428h5.143V24l4.286-4.286h3.428L22.286 12V0zm14.571 11.143l-3.428 3.428h-3.429l-3 3v-3H6.857V1.714h13.714z"/></svg>',
        'instagram' => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>',
        'website'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
        'default'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
    ];
@endphp

<div class="min-h-screen bg-[#121212]">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- ═══════════════════════════════════
         TITLE + RATING + TABS
    ═══════════════════════════════════ --}}
    <div class="pt-8 pb-0">

        {{-- Title --}}
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight leading-tight mb-3">
            {{ $game->title }}
        </h1>

        {{-- Rating + first 2 tags as hero badges --}}
        <div class="flex flex-wrap items-center gap-3 mb-6">
            @if($game->avg_rating)
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-0.5">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $fullStars)
                            {{-- Full star --}}
                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @elseif($halfStar && $i === $fullStars + 1)
                            {{-- Half star --}}
                            <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20">
                                <defs><linearGradient id="half-g"><stop offset="50%" stop-color="#facc15"/><stop offset="50%" stop-color="#374151"/></linearGradient></defs>
                                <path fill="url(#half-g)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @else
                            {{-- Empty star --}}
                            <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endif
                    @endfor
                </div>
                <span class="text-white font-semibold text-sm">{{ number_format($game->avg_rating, 1) }}</span>
            </div>
            @endif

            {{-- Tag badges (hero, max 3) --}}
            @foreach($game->tags->take(3) as $tag)
            <span class="inline-flex items-center gap-1.5 bg-gray-800/70 border border-gray-700/50 text-gray-300 text-xs px-3 py-1.5 rounded-full hover:border-gray-500 transition-colors">
                <span>{{ $tag->emoji }}</span>
                <span>{{ $tag->label }}</span>
            </span>
            @endforeach
        </div>

        {{-- TAB NAV (Overview | Add-Ons | Achievements) --}}
        <div class="flex border-b border-gray-800/50">
            <a href="{{ route('game.show', $game->game_id) }}"
               class="tab-link-active px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors">
                Overview
            </a>
            @if($game->children->count())
            <a href="{{ route('game.addons', $game->game_id) }}"
               class="tab-link-inactive px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors">
                Add-Ons
            </a>
            @endif
            @if($game->achievements->count())
            <a href="{{ route('game.achievements', $game->game_id) }}"
               class="tab-link-inactive px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors">
                Achievements
            </a>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════════════════
         2-COLUMN MAIN LAYOUT
    ═══════════════════════════════════ --}}
    <div class="flex gap-8 items-start mt-6 pb-16">

        {{-- ────────────────────────────
             LEFT COLUMN
        ──────────────────────────── --}}
        <div class="flex-1 min-w-0 space-y-8 fade-in">

            {{-- Cover / Hero image --}}
            <div class="aspect-video rounded-xl overflow-hidden bg-gray-900 shadow-2xl shadow-black/60">
                @if($game->cover_image_url)
                    <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-900">
                        <svg class="w-16 h-16 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            </div>

            {{-- ── main_desc ── --}}
            @if($game->main_desc)
            <p class="text-gray-300 text-base leading-relaxed">{{ $game->main_desc }}</p>
            @endif

            {{-- ── Genres + Features ── --}}
            @if($game->genres->count() || $game->features->count())
            <div class="flex flex-wrap gap-8 pb-4 border-b border-gray-800/50">
                @if($game->genres->count())
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-widest mb-2">Genres</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($game->genres as $genre)
                        <span class="bg-gray-800/80 border border-gray-700/60 text-gray-300 text-sm px-3 py-1 rounded hover:border-gray-500 hover:text-white transition-colors cursor-pointer">
                            {{ $genre->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
                @if($game->features->count())
                <div>
                    <p class="text-gray-500 text-xs uppercase tracking-widest mb-2">Features</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($game->features as $feature)
                        <span class="bg-gray-800/80 border border-gray-700/60 text-gray-300 text-sm px-3 py-1 rounded hover:border-gray-500 hover:text-white transition-colors cursor-pointer">
                            {{ $feature->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endif

            {{-- ── Announce box ── --}}
            @if($game->announce)
            <div class="flex gap-0 rounded-xl overflow-hidden border border-gray-800/60 bg-gray-900/40">
                <div class="w-1 bg-blue-500 flex-shrink-0"></div>
                <div class="px-5 py-4">
                    <p class="text-gray-300 text-sm leading-relaxed">{{ $game->announce }}</p>
                </div>
            </div>
            @endif

            {{-- ── Full desc (with show more) ── --}}
            @if($game->desc)
            <div x-data="{ expanded: false }">
                <h2 class="text-xl font-bold text-white mb-3">{{ $game->title }}</h2>
                <div :class="expanded ? '' : 'line-clamp-6'"
                     class="text-gray-300 text-sm leading-relaxed whitespace-pre-line transition-all duration-300">
                    {{ $game->desc }}
                </div>
                <button @click="expanded = !expanded"
                        class="mt-3 flex items-center gap-1 text-blue-400 hover:text-blue-300 text-sm transition-colors">
                    <span x-text="expanded ? 'Show less' : 'Show more'"></span>
                    <svg class="w-4 h-4 transition-transform duration-200"
                         :class="expanded ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>
            @endif

            {{-- ── Achievements preview (5 cards) ── --}}
            @if($game->achievements->count())
            <div>
                <h3 class="text-lg font-bold text-white mb-4">Available Achievements</h3>
                <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
                    @foreach($game->achievements->take(5) as $ach)
                    <div class="flex-shrink-0 w-28 group">
                        <div class="aspect-square rounded-lg overflow-hidden bg-gray-800 border border-gray-700/50 group-hover:border-gray-500 transition-colors mb-2">
                            @if($ach->icon_url)
                                <img src="{{ $ach->icon_url }}" alt="{{ $ach->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <p class="text-xs font-medium text-gray-300 truncate group-hover:text-white transition-colors">{{ $ach->name }}</p>
                        @if($ach->acv_xp)
                        <p class="text-xs text-gray-500">🏆 {{ $ach->acv_xp }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                <a href="{{ route('game.achievements', $game->game_id) }}"
                   class="mt-3 inline-flex items-center gap-1.5 text-blue-400 hover:text-blue-300 text-sm font-medium transition-colors">
                    See all {{ $game->achievements->first()->total ?? $game->achievements->count() }} achievements
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
            @endif

            {{-- ── Editions / Children cards ── --}}
            @if($game->children->count())
            <div>
                <h3 class="text-xl font-bold text-white mb-4">{{ $game->title }} Editions</h3>
                <div class="space-y-3">
                    @foreach($game->children as $child)
                    @php
                        $childDisc = $child->discounts->filter(fn($d) =>
                            $d->is_active && $d->start_date <= now() && $d->end_date >= now()
                        )->first();
                    @endphp
                    <div class="flex gap-4 bg-gray-900/60 border border-gray-800 rounded-2xl p-4 hover:border-gray-600 hover:bg-gray-900/80 transition-all duration-200 group">
                        {{-- Cover --}}
                        <div class="w-36 aspect-[3/2] rounded-lg overflow-hidden flex-shrink-0 bg-gray-800">
                            @if($child->cover_image_url)
                                <img src="{{ $child->cover_image_url }}" alt="{{ $child->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-800"></div>
                            @endif
                        </div>
                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-gray-400 text-xs mb-1">
                                {{ $child->game_type === 'base_game' ? 'Base Game' : ucwords(str_replace('_', ' ', $child->game_type)) }}
                            </p>
                            <h4 class="text-white font-bold text-base mb-1 group-hover:text-blue-300 transition-colors leading-snug">{{ $child->title }}</h4>
                            @if($child->main_desc)
                            <p class="text-gray-500 text-xs leading-relaxed line-clamp-2 mb-3">{{ $child->main_desc }}</p>
                            @endif
                            <div class="border-t border-gray-800/60 pt-3">
                                {{-- Price --}}
                                <div class="flex items-center gap-2 mb-2">
                                    @if($childDisc)
                                    <span class="bg-blue-600/90 text-white text-xs font-bold px-1.5 py-0.5 rounded">-{{ $child->discount_pct }}%</span>
                                    <span class="text-gray-500 text-sm line-through">IDR {{ number_format($child->base_price, 0, ',', '.') }}</span>
                                    @endif
                                    <span class="text-white font-bold text-base">
                                        @if($child->final_price == 0) Free
                                        @else IDR {{ number_format($child->final_price, 0, ',', '.') }}
                                        @endif
                                    </span>
                                </div>
                                @if($childDisc)
                                <p class="text-gray-500 text-xs mb-3">
                                    Sale ends {{ \Carbon\Carbon::parse($childDisc->end_date)->format('n/j/Y') }} at 10:00 PM
                                </p>
                                @endif
                                {{-- Add to cart --}}
                                <div class="flex items-center gap-2">
                                    @auth
                                    <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="game_id" value="{{ $child->game_id }}">
                                        <button type="submit"
                                                class="w-full bg-blue-500 hover:bg-blue-400 active:scale-95 text-white font-semibold py-2 px-4 rounded-xl text-sm transition-all duration-200">
                                            Add To Cart
                                        </button>
                                    </form>
                                    <form action="{{ route('wishlist.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="game_id" value="{{ $child->game_id }}">
                                        <button type="submit"
                                                class="w-9 h-9 bg-gray-800 hover:bg-gray-700 border border-gray-700 hover:border-gray-500 rounded-xl flex items-center justify-center text-gray-400 hover:text-white transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                            </svg>
                                        </button>
                                    </form>
                                    @else
                                    <a href="{{ route('login') }}"
                                       class="flex-1 text-center bg-blue-500 hover:bg-blue-400 text-white font-semibold py-2 px-4 rounded-xl text-sm transition-all">
                                        Add To Cart
                                    </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── Follow Us (social links) ── --}}
            @if($game->socialLinks->count())
            <div>
                <h3 class="text-lg font-bold text-white mb-4">Follow Us</h3>
                <div class="bg-gray-900/50 border border-gray-800/60 rounded-2xl px-6 py-8 flex justify-center items-center gap-8">
                    @foreach($game->socialLinks as $link)
                    @php $platform = strtolower($link->platform); $icon = $socialIcons[$platform] ?? $socialIcons['default']; @endphp
                    <a href="{{ $link->url }}" target="_blank" rel="noopener noreferrer"
                       class="w-7 h-7 text-gray-400 hover:text-white transition-colors duration-200 hover:scale-110 transform">
                        {!! $icon !!}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── Epic Player Ratings ── --}}
            @if($game->avg_rating)
            <div>
                <h3 class="text-lg font-bold text-white mb-1">Epic Player Ratings</h3>
                <p class="text-gray-500 text-sm mb-5">Captured from players in the Epic Games ecosystem.</p>

                {{-- Big score + stars --}}
                <div class="flex items-center gap-4 mb-6">
                    <span class="text-5xl font-extrabold text-white">{{ number_format($game->avg_rating, 1) }}</span>
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $fullStars)
                                <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @elseif($halfStar && $i === $fullStars + 1)
                                <svg class="w-7 h-7" viewBox="0 0 20 20">
                                    <defs><linearGradient id="half-big"><stop offset="50%" stop-color="#fff"/><stop offset="50%" stop-color="#374151"/></linearGradient></defs>
                                    <path fill="url(#half-big)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @else
                                <svg class="w-7 h-7 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endif
                        @endfor
                    </div>
                </div>

                {{-- Tags 3×2 grid --}}
                @if($game->tags->count())
                <div class="grid grid-cols-3 gap-3">
                    @foreach($game->tags->take(6) as $tag)
                    <div class="bg-gray-800/50 border border-gray-700/50 rounded-xl p-5 text-center hover:border-gray-500 hover:bg-gray-800/70 transition-all duration-200">
                        <div class="w-10 h-10 mx-auto mb-2 flex items-center justify-center">
                            @if(str_starts_with($tag->emoji, 'http'))
                                <img src="{{ $tag->emoji }}" alt="{{ $tag->label }}" class="w-9 h-9 object-contain" onerror="this.replaceWith(document.createTextNode('🎮'))">
                            @else
                                <span class="text-3xl leading-none">{{ $tag->emoji }}</span>
                            @endif
                        </div>
                        <p class="text-gray-400 text-xs mb-1">This game is</p>
                        <p class="text-white text-sm font-semibold leading-tight">{{ $tag->label }}</p>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            @endif

            {{-- ── Ratings & Reviews (Critic) ── --}}
            @if($game->criticReviews->count())
            <div>
                <h3 class="text-xl font-bold text-white mb-5">{{ $game->title }} Ratings &amp; Reviews</h3>

                {{-- Circular stat charts --}}
                @if($firstReview && ($firstReview->percent || $firstReview->avg_score || $firstReview->critic_rating))
                <div class="flex items-start gap-10 mb-8">
                    @if($firstReview->percent)
                    @php $pct = (int)$firstReview->percent; $dash = round($pct/100 * 226.2, 1); @endphp
                    <div class="flex flex-col items-center gap-2">
                        <svg width="80" height="80" viewBox="0 0 80 80">
                            <circle cx="40" cy="40" r="36" fill="none" stroke="#1f2937" stroke-width="7"/>
                            <circle cx="40" cy="40" r="36" fill="none" stroke="#3b82f6" stroke-width="7"
                                    stroke-dasharray="{{ $dash }} 226.2" stroke-linecap="round"
                                    class="donut-ring"/>
                            <text x="40" y="45" text-anchor="middle" fill="white" font-size="14" font-weight="bold" font-family="sans-serif">{{ $pct }}%</text>
                        </svg>
                        <span class="text-gray-400 text-xs text-center">Critics Recommend</span>
                    </div>
                    @endif
                    @if($firstReview->avg_score)
                    @php $score = (int)$firstReview->avg_score; $scoreDash = round($score/100 * 226.2, 1); @endphp
                    <div class="flex flex-col items-center gap-2">
                        <svg width="80" height="80" viewBox="0 0 80 80">
                            <circle cx="40" cy="40" r="36" fill="none" stroke="#1f2937" stroke-width="7"/>
                            <circle cx="40" cy="40" r="36" fill="none" stroke="#3b82f6" stroke-width="7"
                                    stroke-dasharray="{{ $scoreDash }} 226.2" stroke-linecap="round"
                                    class="donut-ring"/>
                            <text x="40" y="45" text-anchor="middle" fill="white" font-size="14" font-weight="bold" font-family="sans-serif">{{ $score }}</text>
                        </svg>
                        <span class="text-gray-400 text-xs text-center">Top Critic Average</span>
                    </div>
                    @endif
                    @if($firstReview->critic_rating)
                    <div class="flex flex-col items-center gap-2">
                        <svg width="80" height="80" viewBox="0 0 80 80">
                            <circle cx="40" cy="40" r="36" fill="none" stroke="#1f2937" stroke-width="7"/>
                            <circle cx="40" cy="40" r="36" fill="none" stroke="#3b82f6" stroke-width="7"
                                    stroke-dasharray="170 226.2" stroke-linecap="round"
                                    class="donut-ring"/>
                            <text x="40" y="45" text-anchor="middle" fill="white" font-size="11" font-weight="bold" font-family="sans-serif">{{ $firstReview->critic_rating }}</text>
                        </svg>
                        <span class="text-gray-400 text-xs text-center">OpenCritic Rating</span>
                    </div>
                    @endif
                    <div class="ml-auto self-center">
                        <a href="#" class="text-blue-400 hover:text-blue-300 text-sm flex items-center gap-1 transition-colors">
                            See All Reviews
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </div>
                </div>
                @endif

                {{-- Review cards (max 3) --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach($game->criticReviews->take(3) as $review)
                    <div class="bg-gray-900/60 border border-gray-800/60 rounded-2xl p-5 flex flex-col hover:border-gray-600 transition-colors duration-200">
                        <div>
                            <p class="text-sm font-semibold text-gray-200">{{ $review->pub }}</p>
                            @if($review->author)
                            <p class="text-xs text-gray-500">by {{ $review->author }}</p>
                            @endif
                        </div>
                        <hr class="border-gray-800 my-3">
                        @if($review->score)
                        <p class="text-2xl font-extrabold text-white mb-3">{{ $review->score }}</p>
                        @endif
                        @if($review->text)
                        <p class="text-gray-400 text-xs leading-relaxed italic flex-1 line-clamp-6">
                            "{{ $review->text }}"
                        </p>
                        @endif
                        <a href="#" class="mt-4 text-blue-400 hover:text-blue-300 text-xs flex items-center gap-1 transition-colors">
                            Read Full Review
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </div>
                    @endforeach
                </div>
                <p class="text-gray-600 text-xs mt-4">Reviews provided by OpenCritic</p>
            </div>
            @endif

            {{-- ── System Requirements ── --}}
            @if($req)
            <div>
                <h3 class="text-xl font-bold text-white mb-4">{{ $game->title }} System Requirements</h3>
                <div class="bg-gray-900/50 border border-gray-800/60 rounded-2xl p-6">
                    {{-- Windows tab header --}}
                    <div class="border-b border-gray-800 mb-6 pb-0">
                        <span class="inline-block pb-3 text-sm font-semibold text-white border-b-2 border-blue-500">Windows</span>
                    </div>
                    {{-- Two columns: Minimum | Recommended --}}
                    <div class="grid grid-cols-2 gap-8">
                        {{-- Minimum --}}
                        <div>
                            <h4 class="font-bold text-white text-sm mb-3">Minimum</h4>
                            @if($req->min_os)         <x-req-row label="Windows OS"        :value="$req->min_os"/>                         @endif
                            @if($req->min_cpu)        <x-req-row label="Windows Processor"  :value="$req->min_cpu"/>                        @endif
                            @if($req->min_ram_gb)     <x-req-row label="Windows Memory"     :value="$req->min_ram_gb . ' GB RAM'"/>         @endif
                            @if($req->min_storage_gb) <x-req-row label="Storage"            :value="$req->min_storage_gb . ' GB available space'"/> @endif
                            @if($req->min_gpu)        <x-req-row label="Graphics"           :value="$req->min_gpu"/>                        @endif
                        </div>
                        {{-- Recommended --}}
                        <div>
                            <h4 class="font-bold text-white text-sm mb-3">Recommended</h4>
                            @if($req->rec_os)         <x-req-row label="Windows OS"        :value="$req->rec_os"/>                         @endif
                            @if($req->rec_cpu)        <x-req-row label="Windows Processor"  :value="$req->rec_cpu"/>                        @endif
                            @if($req->rec_ram_gb)     <x-req-row label="Windows Memory"     :value="$req->rec_ram_gb . ' GB RAM'"/>         @endif
                            @if($req->rec_storage_gb) <x-req-row label="Storage"            :value="$req->rec_storage_gb . ' GB available space'"/> @endif
                            @if($req->rec_gpu)        <x-req-row label="Graphics"           :value="$req->rec_gpu"/>                        @endif
                        </div>
                    </div>
                    {{-- Login + Languages --}}
                    @if($req->policy || $req->languange)
                    <div class="mt-4 pt-4 border-t border-gray-800 grid grid-cols-2 gap-8">
                        @if($req->policy)
                        <x-req-row label="Login Accounts Required" :value="$req->policy"/>
                        @endif
                        @if($req->languange)
                        <x-req-row label="Languages Supported" :value="$req->languange"/>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @endif

        </div>{{-- END LEFT COLUMN --}}

        {{-- ────────────────────────────
             RIGHT SIDEBAR (sticky)
        ──────────────────────────── --}}
        <div class="w-72 lg:w-80 flex-shrink-0">
        <div class="sticky top-4 space-y-0">

            {{-- BUY PANEL --}}
            <div class="bg-gray-900/70 border border-gray-800/60 rounded-2xl overflow-hidden">
                {{-- Game icon/logo image --}}
                @if($game->icon_url)
                <div class="aspect-video overflow-hidden">
                    <img src="{{ $game->icon_url }}" alt="{{ $game->title }}" class="w-full h-full object-cover">
                </div>
                @endif

                <div class="p-4 space-y-3">
                    {{-- Game type label --}}
                    <p class="text-gray-400 text-sm">
                        {{ $game->game_type === 'base_game' ? 'Base Game' : ucwords(str_replace('_', ' ', $game->game_type)) }}
                    </p>

                    {{-- Price block --}}
                    @if($game->final_price == 0)
                        <p class="text-2xl font-extrabold text-white">Free</p>
                    @elseif($game->discount_pct > 0)
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="bg-blue-600 text-white text-xs font-bold px-1.5 py-0.5 rounded">-{{ $game->discount_pct }}%</span>
                                <span class="text-gray-500 text-sm line-through">IDR {{ number_format($game->base_price, 0, ',', '.') }}*</span>
                            </div>
                            <p class="text-2xl font-extrabold text-white">IDR {{ number_format($game->final_price, 0, ',', '.') }}</p>
                            @if($activeDisc)
                            <p class="text-gray-500 text-xs mt-1">
                                Sale ends {{ \Carbon\Carbon::parse($activeDisc->end_date)->format('n/j/Y') }} at 10:00 PM
                            </p>
                            @endif
                        </div>
                    @else
                        <p class="text-2xl font-extrabold text-white">IDR {{ number_format($game->base_price, 0, ',', '.') }}</p>
                    @endif

                    {{-- Buy Now + Cart / Login CTA --}}
                    @auth
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="game_id" value="{{ $game->game_id }}">
                        <div class="flex gap-2">
                            <button type="submit"
                                    class="flex-1 bg-[#0078f2] hover:bg-blue-500 active:scale-[.98] text-white font-bold py-3 px-4 rounded-xl text-sm transition-all duration-200 shadow-lg shadow-blue-600/20">
                                {{ $game->final_price == 0 ? 'Get' : 'Buy Now' }}
                            </button>
                            <button type="submit"
                                    class="w-11 h-11 bg-gray-800 hover:bg-gray-700 border border-gray-700 hover:border-gray-500 rounded-xl flex items-center justify-center text-gray-400 hover:text-white transition-all duration-200 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </button>
                        </div>
                    </form>
                    {{-- Wishlist --}}
                    <form action="{{ route('wishlist.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="game_id" value="{{ $game->game_id }}">
                        <button type="submit"
                                class="w-full bg-gray-800 hover:bg-gray-700 border border-gray-700/60 hover:border-gray-500 text-gray-300 hover:text-white font-medium py-3 px-4 rounded-xl text-sm flex items-center justify-center gap-2 transition-all duration-200 active:scale-[.98]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                            Wishlist
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}"
                       class="block w-full text-center bg-[#0078f2] hover:bg-blue-500 text-white font-bold py-3 px-4 rounded-xl text-sm transition-all duration-200">
                        {{ $game->final_price == 0 ? 'Get' : 'Buy Now' }}
                    </a>
                    @endauth

                    {{-- Epic Rewards (jika tidak gratis) --}}
                    @if($game->final_price > 0)
                    <div class="flex items-center justify-between pt-1">
                        <span class="text-gray-400 text-sm">Epic Rewards</span>
                        <div class="flex items-center gap-1.5 text-sm font-medium text-white">
                            <span>Earn 5% Back</span>
                            <span class="w-5 h-5 bg-blue-600 rounded-full inline-flex items-center justify-center text-white text-xs font-bold">›</span>
                        </div>
                    </div>
                    @endif

                    {{-- Refund Type --}}
                    @if($game->refund_type)
                    <div class="flex items-center justify-between pt-1 border-t border-gray-800/50">
                        <span class="text-gray-400 text-sm">Refund Type</span>
                        <div class="flex items-center gap-1">
                            <span class="text-white text-sm">{{ $game->refund_type }}</span>
                            <span class="text-gray-600 text-xs cursor-help" title="Refund policy">?</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- METADATA --}}
            <div class="border-t border-gray-800/30">
                @if($game->developer)
                <div class="flex justify-between items-start py-3 border-b border-gray-800/40">
                    <span class="text-gray-500 text-sm">Developer</span>
                    <span class="text-white text-sm text-right ml-4 leading-snug">{{ $game->developer->name }}</span>
                </div>
                @endif
                @if($game->publisher)
                <div class="flex justify-between items-start py-3 border-b border-gray-800/40">
                    <span class="text-gray-500 text-sm">Publisher</span>
                    <span class="text-white text-sm text-right ml-4 leading-snug">{{ $game->publisher->name }}</span>
                </div>
                @endif
                @if($game->release_date)
                <div class="flex justify-between items-center py-3 border-b border-gray-800/40">
                    <span class="text-gray-500 text-sm">Release Date</span>
                    <span class="text-white text-sm">{{ $game->release_date->format('m/d/y') }}</span>
                </div>
                @endif
                @if($game->platforms->count())
                <div class="flex justify-between items-center py-3 border-b border-gray-800/40">
                    <span class="text-gray-500 text-sm">Platform</span>
                    <div class="flex items-center gap-1.5">
                        @foreach($game->platforms as $platform)
                            @if(stripos($platform->platform, 'win') !== false)
                            {{-- Windows icon --}}
                            <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M0 3.449L9.75 2.1v9.451H0m10.949-9.602L24 0v11.4H10.949M0 12.6h9.75v9.451L0 20.699M10.949 12.6H24V24l-12.9-1.801"/>
                            </svg>
                            @else
                            <span class="text-gray-300 text-xs">{{ $platform->platform }}</span>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- See All Editions --}}
            @if($game->children->count())
            <a href="{{ route('game.addons', $game->game_id) }}"
               class="flex items-center justify-between py-3.5 border-t border-b border-gray-800/40 text-gray-300 hover:text-white text-sm transition-colors group">
                <span>See All Editions and Add-Ons</span>
                <svg class="w-4 h-4 text-gray-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </a>
            @endif

            {{-- Share + Report --}}
            <div class="flex gap-2 pt-4">
                <button class="flex-1 bg-gray-800 hover:bg-gray-700 border border-gray-700/50 hover:border-gray-500 text-gray-400 hover:text-white text-sm py-2.5 rounded-xl flex items-center justify-center gap-2 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                    </svg>
                    Share
                </button>
                <button class="flex-1 bg-gray-800 hover:bg-gray-700 border border-gray-700/50 hover:border-gray-500 text-gray-400 hover:text-white text-sm py-2.5 rounded-xl flex items-center justify-center gap-2 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                    </svg>
                    Report
                </button>
            </div>

        </div>
        </div>{{-- END RIGHT SIDEBAR --}}

    </div>{{-- END 2-COLUMN --}}
</div>
</div>
@endsection