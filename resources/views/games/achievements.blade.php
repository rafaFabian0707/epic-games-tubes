@extends('layouts.app')
@section('title', $game->title . ' — Achievements — Epic Games Store')

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
@php
    $firstAch = $game->achievements->first();
    $totalAch = $firstAch->total ?? $game->achievements->count();
    $totalXp  = $firstAch->avail_xp ?? $game->achievements->sum('acv_xp');
@endphp

<div class="min-h-screen bg-[#121212]">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- ═══ PAGE HEADER ═══ --}}
    <div class="pt-8 pb-0">
        <h1 class="text-4xl sm:text-5xl font-extrabold text-white tracking-tight leading-tight mb-6">
            {{ $game->title }}
        </h1>

        {{-- TAB NAV --}}
        <div class="flex border-b border-gray-800/50">
            <a href="{{ route('game.show', $game->game_id) }}"
               class="tab-link-inactive px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors">
                Overview
            </a>
            @if($game->children->count())
            <a href="{{ route('game.addons', $game->game_id) }}"
               class="tab-link-inactive px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors">
                Add-Ons
            </a>
            @endif
            <a href="{{ route('game.achievements', $game->game_id) }}"
               class="tab-link-active px-4 py-3 text-sm font-medium whitespace-nowrap transition-colors">
                Achievements
            </a>
        </div>
    </div>

    {{-- ═══ CONTENT ═══ --}}
    <div class="mt-6 pb-16 fade-in">

        @if($game->achievements->count())

        {{-- HERO BANNER --}}
        <div class="flex rounded-2xl overflow-hidden border border-gray-800/50 bg-gray-900/40 mb-8">
            {{-- Cover image --}}
            <div class="w-64 flex-shrink-0 aspect-[4/3] overflow-hidden">
                @if($game->cover_image_url)
                    <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Stats --}}
            <div class="flex items-center px-10 py-8 gap-16">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Available Achievements</p>
                    <p class="text-4xl font-extrabold text-white">{{ $totalAch }} Achievements</p>
                </div>
                @if($totalXp)
                <div>
                    <p class="text-gray-500 text-sm mb-1">Available XP</p>
                    <p class="text-4xl font-extrabold text-white">{{ number_format($totalXp) }} XP</p>
                </div>
                @endif
            </div>
        </div>

        {{-- SORT + COUNT --}}
        <div class="flex items-center gap-3 mb-5">
            <h2 class="text-white font-bold text-lg">
                Achievements ({{ $totalAch }})
                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full border border-gray-600 text-gray-500 text-xs cursor-help ml-1" title="Achievement info">ⓘ</span>
            </h2>
            <div class="ml-auto flex items-center gap-2">
                <span class="text-gray-400 text-sm">Sort</span>
                <button class="flex items-center gap-1 text-white text-sm font-medium hover:text-gray-300 transition-colors bg-gray-800/60 border border-gray-700/50 px-3 py-1.5 rounded-lg">
                    Alphabetical
                    <svg class="w-3.5 h-3.5 text-gray-400 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- ACHIEVEMENT LIST (full, vertical) --}}
        <div class="space-y-0 border border-gray-800/50 rounded-2xl overflow-hidden">
            @foreach($game->achievements as $ach)
            <div class="flex items-center gap-4 px-5 py-4
                        border-b border-gray-800/40 last:border-b-0
                        bg-gray-900/30 hover:bg-gray-900/60
                        transition-colors duration-150 group">

                {{-- Icon --}}
                <div class="w-14 h-14 rounded-xl overflow-hidden flex-shrink-0 bg-gray-800 border border-gray-700/40">
                    @if($ach->icon_url)
                        <img src="{{ $ach->icon_url }}" alt="{{ $ach->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-7 h-7 text-yellow-500/60" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Text --}}
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-semibold group-hover:text-blue-200 transition-colors truncate">
                        {{ $ach->name }}
                    </p>
                    @if($ach->desc)
                    <p class="text-gray-500 text-xs mt-0.5 leading-relaxed">{{ $ach->desc }}</p>
                    @endif
                </div>

                {{-- XP + Percent --}}
                <div class="flex-shrink-0 text-right">
                    @if($ach->acv_xp)
                    <p class="text-white text-sm font-semibold">{{ $ach->acv_xp }} XP
                        <span class="text-yellow-500">🏆</span>
                    </p>
                    @endif
                    @if($ach->percent)
                    <p class="text-gray-500 text-xs mt-0.5">{{ $ach->percent }}% of players unlock</p>
                    @endif
                </div>

            </div>
            @endforeach
        </div>

        @else

        {{-- Empty state --}}
        <div class="flex rounded-2xl overflow-hidden border border-gray-800/50 bg-gray-900/40 mb-8">
            @if($game->cover_image_url)
            <div class="w-64 flex-shrink-0 aspect-[4/3] overflow-hidden">
                <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}"
                     class="w-full h-full object-cover opacity-60">
            </div>
            @endif
            <div class="flex items-center px-10 py-8">
                <div>
                    <p class="text-gray-500 text-sm mb-1">Available Achievements</p>
                    <p class="text-3xl font-extrabold text-gray-600">No Achievements</p>
                    <p class="text-gray-600 text-sm mt-2">Game ini belum memiliki achievement.</p>
                </div>
            </div>
        </div>

        @endif

    </div>{{-- END content --}}
</div>
</div>
@endsection