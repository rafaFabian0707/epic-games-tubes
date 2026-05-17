@extends('layouts.app')
@section('title', $game->title . ' — DLC & Add-Ons — Epic Games Store')

@push('styles')
<style>
    .tab-link-active   { color:#fff; border-bottom:2px solid #3b82f6; }
    .tab-link-inactive { color:#9ca3af; border-bottom:2px solid transparent; }
    .tab-link-inactive:hover { color:#e5e7eb; }
    .fade-in { animation: fadeIn .35s ease forwards; }
    @@keyframes fadeIn { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:translateY(0)} }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-[#121212]">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- ═══ PAGE HEADER ═══ --}}
    <div class="pt-8 pb-0">
        {{-- Breadcrumb game title --}}
        <p class="text-gray-400 text-sm mb-1">
            <a href="{{ route('game.show', $game->game_id) }}"
               class="hover:text-white transition-colors">{{ $game->title }}</a>
        </p>
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight leading-tight mb-6">
            DLC &amp; Add-Ons
        </h1>

        {{-- TAB NAV --}}
        <div class="flex border-b border-gray-800/50">
            <a href="{{ route('game.show', $game->game_id) }}"
               class="tab-link-inactive px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors">
                Overview
            </a>
            <a href="{{ route('game.addons', $game->game_id) }}"
               class="tab-link-active px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors">
                Add-Ons
            </a>
            @if($game->achievements->count())
            <a href="{{ route('game.achievements', $game->game_id) }}"
               class="tab-link-inactive px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors">
                Achievements
            </a>
            @endif
        </div>
    </div>

    {{-- ═══ CONTENT ═══ --}}
    <div class="flex gap-8 items-start mt-8 pb-16 fade-in">

        {{-- LEFT: Add-On Grid --}}
        <div class="flex-1 min-w-0">

            @if($game->children->count())

            {{-- Show filter label --}}
            <div class="flex items-center gap-3 mb-6">
                <span class="text-gray-400 text-sm">Show:</span>
                <button class="flex items-center gap-1 text-white text-sm font-medium hover:text-gray-300 transition-colors">
                    All
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>

            {{-- Add-on cards — 4 columns --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($game->children as $addon)
                @php
                    $addonDisc = $addon->discounts->filter(fn($d) =>
                        $d->is_active && $d->start_date <= now() && $d->end_date >= now()
                    )->first();
                @endphp
                <a href="{{ route('game.show', $addon->game_id) }}"
                   class="group flex flex-col bg-transparent rounded-xl overflow-hidden hover:-translate-y-0.5 transition-all duration-200">

                    {{-- Cover art --}}
                    <div class="aspect-square rounded-xl overflow-hidden bg-gray-900 border border-gray-800/40
                                group-hover:border-gray-600/60 transition-colors duration-200 mb-3">
                        @if($addon->cover_image_url)
                            <img src="{{ $addon->cover_image_url }}" alt="{{ $addon->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-900">
                                <svg class="w-10 h-10 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="px-0.5">
                        <p class="text-gray-500 text-xs mb-1">
                            {{ $addon->game_type === 'dlc' ? 'Add-On' : ucwords(str_replace('_',' ',$addon->game_type)) }}
                        </p>
                        <p class="text-gray-200 text-sm font-semibold leading-snug line-clamp-2
                                  group-hover:text-white transition-colors duration-200 mb-2">
                            {{ $addon->title }}
                        </p>

                        {{-- Price --}}
                        @if($addon->final_price == 0)
                            <span class="text-white text-sm font-bold">Free</span>
                        @elseif($addonDisc)
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <span class="bg-blue-600/90 text-white text-xs font-bold px-1.5 py-0.5 rounded">
                                    -{{ $addon->discount_pct }}%
                                </span>
                                <span class="text-gray-500 text-xs line-through">
                                    IDR {{ number_format($addon->base_price, 0, ',', '.') }}
                                </span>
                            </div>
                            <span class="text-white text-sm font-bold block mt-0.5">
                                IDR {{ number_format($addon->final_price, 0, ',', '.') }}
                            </span>
                        @else
                            <span class="text-white text-sm font-bold">
                                IDR {{ number_format($addon->base_price, 0, ',', '.') }}
                            </span>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>

            @else
            <div class="text-center py-24 text-gray-600">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <p class="text-sm">Tidak ada add-on untuk game ini.</p>
            </div>
            @endif
        </div>

        {{-- RIGHT: Filter sidebar --}}
        <div class="w-64 flex-shrink-0">
        <div class="sticky top-4 bg-gray-900/60 border border-gray-800/60 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-800/60">
                <h3 class="text-white font-bold text-base">Filters</h3>
            </div>

            {{-- Search keywords --}}
            <div class="px-5 py-4 border-b border-gray-800/40">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-500"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                    </svg>
                    <input type="text" placeholder="Keywords"
                           class="w-full bg-gray-800 border border-gray-700/60 rounded-lg text-sm text-gray-300
                                  placeholder-gray-600 pl-8 pr-3 py-2 focus:outline-none focus:border-gray-500
                                  transition-colors">
                </div>
            </div>

            {{-- Price filter --}}
            <div class="px-5 py-4 border-b border-gray-800/40">
                <button class="w-full flex items-center justify-between text-gray-300 hover:text-white text-sm transition-colors">
                    <span class="font-medium">Price</span>
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>

            {{-- Types filter --}}
            <div class="px-5 py-4 border-b border-gray-800/40">
                <button class="w-full flex items-center justify-between text-gray-300 hover:text-white text-sm transition-colors">
                    <span class="font-medium">Types</span>
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>

            {{-- Platform filter --}}
            <div class="px-5 py-4">
                <button class="w-full flex items-center justify-between text-gray-300 hover:text-white text-sm transition-colors">
                    <span class="font-medium">Platform</span>
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>
        </div>
        </div>

    </div>{{-- END flex --}}
</div>
</div>
@endsection