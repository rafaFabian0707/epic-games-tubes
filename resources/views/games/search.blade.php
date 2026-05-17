@extends('layouts.app')
@section('title', $keyword ? "Hasil pencarian: {$keyword}" : 'Cari Game — Epic Games Store')

@push('styles')
<style>
    .fade-in { animation: fadeIn .3s ease forwards; }
    @@keyframes fadeIn { from{opacity:0;transform:translateY(5px)} to{opacity:1;transform:translateY(0)} }
    .game-card-hover { transition: transform .2s ease, box-shadow .2s ease; }
    .game-card-hover:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.5); }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-[#121212]">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Search header --}}
    <div class="mb-8">
        {{-- Search form (besar, centred) --}}
        <form action="{{ route('store.search') }}" method="GET" class="max-w-2xl">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input type="text" name="q" value="{{ $keyword }}"
                       placeholder="Cari game..."
                       autofocus
                       class="w-full bg-gray-900 border border-gray-700 hover:border-gray-600
                              focus:border-blue-500 focus:ring-1 focus:ring-blue-500/40
                              text-white text-base placeholder-gray-500
                              pl-12 pr-4 py-3.5 rounded-2xl outline-none
                              transition-all duration-200">
                @if($keyword)
                <a href="{{ route('store.search') }}"
                   class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
                @endif
            </div>
        </form>

        {{-- Result count / state label --}}
        <div class="mt-4">
            @if($keyword)
                @if($games->count())
                    <p class="text-gray-400 text-sm">
                        <span class="text-white font-semibold">{{ $games->count() }}</span>
                        hasil untuk
                        "<span class="text-blue-400 font-medium">{{ $keyword }}</span>"
                    </p>
                @else
                    <p class="text-gray-400 text-sm">
                        Tidak ada hasil untuk
                        "<span class="text-blue-400 font-medium">{{ $keyword }}</span>"
                    </p>
                @endif
            @else
                <p class="text-gray-500 text-sm">Ketik minimal 2 karakter untuk mencari.</p>
            @endif
        </div>
    </div>

    {{-- Results grid --}}
    @if($keyword && $games->count())
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-4 fade-in">
        @foreach($games as $game)
        @php
            $disc = $game->discounts->filter(fn($d) =>
                $d->is_active && $d->start_date <= now() && $d->end_date >= now()
            )->first();
        @endphp
        <a href="{{ route('game.show', $game->game_id) }}"
           class="group flex flex-col game-card-hover">

            {{-- Cover --}}
            <div class="aspect-[3/4] rounded-xl overflow-hidden bg-gray-900 border border-gray-800/50
                        group-hover:border-gray-600/60 transition-colors duration-200 mb-3 flex-shrink-0">
                @if($game->cover_image_url)
                    <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}"
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
                @if($game->publisher)
                <p class="text-gray-500 text-xs mb-0.5 truncate">{{ $game->publisher->name }}</p>
                @endif
                <p class="text-gray-200 text-sm font-semibold leading-snug line-clamp-2
                          group-hover:text-white transition-colors duration-200 mb-1.5">
                    {{ $game->title }}
                </p>

                {{-- Price --}}
                @if($game->base_price == 0 || is_null($game->base_price))
                    <span class="text-white text-sm font-bold">Gratis</span>
                @elseif($disc)
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="bg-blue-600/90 text-white text-xs font-bold px-1.5 py-0.5 rounded">
                            -{{ $game->discount_pct }}%
                        </span>
                        <span class="text-gray-500 text-xs line-through">
                            IDR {{ number_format($game->base_price, 0, ',', '.') }}
                        </span>
                    </div>
                    <span class="text-white text-sm font-bold block">
                        IDR {{ number_format($game->final_price, 0, ',', '.') }}
                    </span>
                @else
                    <span class="text-white text-sm font-bold">
                        IDR {{ number_format($game->base_price, 0, ',', '.') }}
                    </span>
                @endif
            </div>
        </a>
        @endforeach
    </div>

    {{-- Tip FULLTEXT --}}
    <p class="mt-8 text-gray-700 text-xs text-center">
        Pencarian menggunakan MySQL FULLTEXT index pada kolom title, main_desc, dan desc.
    </p>

    @elseif($keyword && $games->isEmpty())

    {{-- Empty state --}}
    <div class="text-center py-24 fade-in">
        <svg class="w-16 h-16 mx-auto mb-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                  d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <h2 class="text-xl font-bold text-gray-400 mb-2">Tidak ada hasil</h2>
        <p class="text-gray-600 text-sm max-w-sm mx-auto">
            Coba kata kunci yang berbeda atau lebih umum. Pastikan FULLTEXT index sudah dibuat di migration.
        </p>
        <a href="{{ route('store') }}"
           class="inline-flex items-center gap-2 mt-6 text-blue-400 hover:text-blue-300 text-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
            </svg>
            Kembali ke Store
        </a>
    </div>

    @else

    {{-- Initial state: no keyword yet —show browse prompt --}}
    <div class="text-center py-24 fade-in">
        <svg class="w-16 h-16 mx-auto mb-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
        </svg>
        <p class="text-gray-500 text-sm">Mulai ketik di kotak pencarian di atas.</p>
        <a href="{{ route('store') }}"
           class="inline-flex items-center gap-2 mt-4 text-blue-400 hover:text-blue-300 text-sm transition-colors">
            Atau lihat semua game di Store
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>

    @endif

</div>
</div>
@endsection

@push('scripts')
<script>
// Live search: submit form saat user berhenti mengetik (debounce 400ms)
(function () {
    const input = document.querySelector('input[name="q"]');
    if (!input) return;
    let timer;
    input.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(() => {
            if (this.value.length === 0 || this.value.length >= 2) {
                this.closest('form').submit();
            }
        }, 400);
    });
})();
</script>
@endpush