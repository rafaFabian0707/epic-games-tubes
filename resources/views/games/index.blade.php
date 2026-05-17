@extends('layouts.app')
@section('title', 'Store — Epic Games')

@push('styles')
<style>
    .filter-scroll::-webkit-scrollbar { width: 4px; }
    .filter-scroll::-webkit-scrollbar-track { background: transparent; }
    .filter-scroll::-webkit-scrollbar-thumb { background: #374151; border-radius: 2px; }
    .filter-scroll::-webkit-scrollbar-thumb:hover { background: #4b5563; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-[#121212]">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Page header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-white">Discover Games</h1>
        <p class="text-gray-500 text-sm mt-1">
            {{ $games->total() }} game ditemukan
            @if(request()->hasAny(['genre','price','platform','feature']))
                <span class="text-blue-400 ml-1">· Filter aktif</span>
            @endif
        </p>
    </div>

    <div class="flex gap-6">

        {{-- ═══════════════════════════════
             SIDEBAR FILTER
        ═══════════════════════════════ --}}
        <aside class="hidden lg:block w-56 flex-shrink-0 space-y-1">

            {{-- ── Harga ── --}}
            <div class="pb-4 border-b border-gray-800/60">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 px-1">Harga</h3>
                <div class="space-y-0.5">
                    @foreach([
                        ''         => 'Semua',
                        'free'     => 'Gratis',
                        'under150' => 'Di bawah Rp150K',
                        'under300' => 'Di bawah Rp300K',
                        'under600' => 'Di bawah Rp600K',
                    ] as $val => $label)
                    <a href="{{ request()->fullUrlWithQuery(['price' => $val, 'page' => 1]) }}"
                       class="flex items-center gap-2 text-sm px-3 py-2 rounded-lg transition-colors
                              {{ request('price', '') == $val
                                 ? 'bg-blue-600/90 text-white font-medium'
                                 : 'text-gray-400 hover:text-white hover:bg-gray-800/70' }}">
                        @if(request('price', '') == $val)
                        <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        @else
                        <span class="w-3"></span>
                        @endif
                        {{ $label }}
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- ── Platform ── --}}
            @if($platforms->count())
            <div class="py-4 border-b border-gray-800/60">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 px-1">Platform</h3>
                <div class="space-y-0.5">
                    {{-- "Semua" option --}}
                    <a href="{{ request()->fullUrlWithQuery(['platform' => '', 'page' => 1]) }}"
                       class="flex items-center gap-2 text-sm px-3 py-2 rounded-lg transition-colors
                              {{ !request('platform')
                                 ? 'bg-blue-600/90 text-white font-medium'
                                 : 'text-gray-400 hover:text-white hover:bg-gray-800/70' }}">
                        @if(!request('platform'))
                        <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        @else
                        <span class="w-3"></span>
                        @endif
                        Semua
                    </a>
                    @foreach($platforms as $platform)
                    <a href="{{ request()->fullUrlWithQuery(['platform' => $platform->platform_id, 'page' => 1]) }}"
                       class="flex items-center gap-2 text-sm px-3 py-2 rounded-lg transition-colors
                              {{ request('platform') == $platform->platform_id
                                 ? 'bg-blue-600/90 text-white font-medium'
                                 : 'text-gray-400 hover:text-white hover:bg-gray-800/70' }}">
                        @if(request('platform') == $platform->platform_id)
                        <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        @else
                        <span class="w-3"></span>
                        @endif
                        {{ $platform->platform }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── Genre ── --}}
            @if($genres->count())
            <div class="py-4 border-b border-gray-800/60">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 px-1">Genre</h3>
                <div class="space-y-0.5 max-h-52 overflow-y-auto filter-scroll pr-1">
                    <a href="{{ request()->fullUrlWithQuery(['genre' => '', 'page' => 1]) }}"
                       class="flex items-center gap-2 text-sm px-3 py-2 rounded-lg transition-colors
                              {{ !request('genre')
                                 ? 'bg-blue-600/90 text-white font-medium'
                                 : 'text-gray-400 hover:text-white hover:bg-gray-800/70' }}">
                        @if(!request('genre'))<svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        @else<span class="w-3"></span>@endif
                        Semua
                    </a>
                    @foreach($genres as $genre)
                    <a href="{{ request()->fullUrlWithQuery(['genre' => $genre->genre_id, 'page' => 1]) }}"
                       class="flex items-center gap-2 text-sm px-3 py-2 rounded-lg transition-colors
                              {{ request('genre') == $genre->genre_id
                                 ? 'bg-blue-600/90 text-white font-medium'
                                 : 'text-gray-400 hover:text-white hover:bg-gray-800/70' }}">
                        @if(request('genre') == $genre->genre_id)<svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        @else<span class="w-3"></span>@endif
                        {{ $genre->name }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── Features ── --}}
            @if($features->count())
            <div class="py-4 border-b border-gray-800/60">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 px-1">Fitur</h3>
                <div class="space-y-0.5 max-h-52 overflow-y-auto filter-scroll pr-1">
                    <a href="{{ request()->fullUrlWithQuery(['feature' => '', 'page' => 1]) }}"
                       class="flex items-center gap-2 text-sm px-3 py-2 rounded-lg transition-colors
                              {{ !request('feature')
                                 ? 'bg-blue-600/90 text-white font-medium'
                                 : 'text-gray-400 hover:text-white hover:bg-gray-800/70' }}">
                        @if(!request('feature'))<svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        @else<span class="w-3"></span>@endif
                        Semua
                    </a>
                    @foreach($features as $feature)
                    <a href="{{ request()->fullUrlWithQuery(['feature' => $feature->feature_id, 'page' => 1]) }}"
                       class="flex items-center gap-2 text-sm px-3 py-2 rounded-lg transition-colors
                              {{ request('feature') == $feature->feature_id
                                 ? 'bg-blue-600/90 text-white font-medium'
                                 : 'text-gray-400 hover:text-white hover:bg-gray-800/70' }}">
                        @if(request('feature') == $feature->feature_id)<svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        @else<span class="w-3"></span>@endif
                        {{ $feature->name }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── Reset Filter ── --}}
            @if(request()->hasAny(['genre','price','platform','feature','sort']))
            <div class="pt-4">
                <a href="{{ route('store') }}"
                   class="flex items-center justify-center gap-2 text-sm py-2 px-3 rounded-lg
                          border border-red-900/60 text-red-400 hover:text-red-300
                          hover:border-red-700 hover:bg-red-950/30 transition-all duration-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reset Filter
                </a>
            </div>
            @endif

        </aside>

        {{-- ═══════════════════════════════
             GAME GRID
        ═══════════════════════════════ --}}
        <div class="flex-1 min-w-0">

            {{-- Sort bar --}}
            <div class="flex items-center justify-between mb-5">
                {{-- Active filter badges --}}
                <div class="flex flex-wrap items-center gap-2">
                    @if(request('price'))
                    @php $priceLabels = ['free'=>'Gratis','under150'=>'<Rp150K','under300'=>'<Rp300K','under600'=>'<Rp600K']; @endphp
                    <span class="inline-flex items-center gap-1 bg-blue-600/20 border border-blue-600/40 text-blue-300 text-xs px-2.5 py-1 rounded-full">
                        {{ $priceLabels[request('price')] ?? request('price') }}
                        <a href="{{ request()->fullUrlWithQuery(['price' => '', 'page' => 1]) }}" class="hover:text-white ml-0.5">×</a>
                    </span>
                    @endif
                    @if(request('platform'))
                    @php $pl = $platforms->firstWhere('platform_id', request('platform')); @endphp
                    @if($pl)
                    <span class="inline-flex items-center gap-1 bg-blue-600/20 border border-blue-600/40 text-blue-300 text-xs px-2.5 py-1 rounded-full">
                        {{ $pl->platform }}
                        <a href="{{ request()->fullUrlWithQuery(['platform' => '', 'page' => 1]) }}" class="hover:text-white ml-0.5">×</a>
                    </span>
                    @endif
                    @endif
                    @if(request('genre'))
                    @php $g = $genres->firstWhere('genre_id', request('genre')); @endphp
                    @if($g)
                    <span class="inline-flex items-center gap-1 bg-blue-600/20 border border-blue-600/40 text-blue-300 text-xs px-2.5 py-1 rounded-full">
                        {{ $g->name }}
                        <a href="{{ request()->fullUrlWithQuery(['genre' => '', 'page' => 1]) }}" class="hover:text-white ml-0.5">×</a>
                    </span>
                    @endif
                    @endif
                    @if(request('feature'))
                    @php $ft = $features->firstWhere('feature_id', request('feature')); @endphp
                    @if($ft)
                    <span class="inline-flex items-center gap-1 bg-blue-600/20 border border-blue-600/40 text-blue-300 text-xs px-2.5 py-1 rounded-full">
                        {{ $ft->name }}
                        <a href="{{ request()->fullUrlWithQuery(['feature' => '', 'page' => 1]) }}" class="hover:text-white ml-0.5">×</a>
                    </span>
                    @endif
                    @endif
                    @if(!request()->hasAny(['genre','price','platform','feature']))
                    <span class="text-gray-600 text-sm">Halaman {{ $games->currentPage() }} dari {{ $games->lastPage() }}</span>
                    @endif
                </div>

                {{-- Sort dropdown --}}
                <select onchange="window.location=this.value"
                        class="bg-gray-800 border border-gray-700 hover:border-gray-600 text-white text-sm
                               rounded-xl px-3 py-2 focus:outline-none focus:border-blue-500
                               cursor-pointer transition-colors">
                    @foreach(['newest'=>'Terbaru','rating'=>'Rating Tertinggi','price_asc'=>'Harga Terendah','price_desc'=>'Harga Tertinggi','name'=>'A-Z'] as $val => $label)
                    <option value="{{ request()->fullUrlWithQuery(['sort' => $val]) }}"
                            {{ request('sort', 'newest') == $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Grid --}}
            @if($games->isEmpty())
            <div class="text-center py-24 text-gray-600">
                <svg class="w-14 h-14 mx-auto mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                          d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm">Tidak ada game yang sesuai filter.</p>
                <a href="{{ route('store') }}" class="mt-3 inline-block text-blue-400 hover:text-blue-300 text-sm transition-colors">
                    Reset filter
                </a>
            </div>
            @else
            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($games as $game)
                @include('components.game-card', ['game' => $game])
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $games->withQueryString()->links() }}
            </div>
            @endif

        </div>{{-- end game grid --}}
    </div>
</div>
</div>
@endsection