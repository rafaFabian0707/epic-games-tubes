<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jelajahi — Epic Games Store</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="antialiased pb-20 min-w-full max-w-full w-full flex flex-col bg-background text-primary">

    <x-navbar />

    {{-- ===================== MAIN CONTENT ===================== --}}
    <div class="px-40 mt-10 max-w-full w-full flex items-start gap-x-6">

        {{-- ===== KIRI: GENRE SWIPER + GRID GAME ===== --}}
        <div class="flex flex-col gap-6 flex-1 min-w-0"
             x-data="{}"
             x-init="
                const swiper = new Swiper('.gameSwiper', {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    navigation: {
                        nextEl: '.swiper-btn-next',
                        prevEl: '.swiper-btn-prev',
                    }
                });
             ">

            {{-- ---- Genre Populer Swiper ---- --}}
            @if($genres->isNotEmpty())
            <div class="swiper gameSwiper w-full overflow-hidden">
                <div class="flex w-full items-center justify-between py-4">
                    <h1 class="text-4xl font-extrabold text-primary">Genre Populer</h1>
                    <div class="flex gap-2">
                        <button class="swiper-btn-prev py-2 px-[11px] cursor-pointer rounded-full bg-white/20 hover:bg-white/30 rotate-180 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-2" viewBox="0 0 5 9">
                                <path stroke="currentColor" d="M1 1l3 3.5L1 8" fill="none" fill-rule="evenodd"></path>
                            </svg>
                        </button>
                        <button class="swiper-btn-next py-2 px-[11px] cursor-pointer rounded-full bg-white/20 hover:bg-white/30 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-2" viewBox="0 0 5 9">
                                <path stroke="currentColor" d="M1 1l3 3.5L1 8" fill="none" fill-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="w-full swiper-wrapper">
                    {{-- Chunk genre per 4 untuk tiap slide --}}
                    @foreach ($genres->chunk(4) as $genreChunk)
                    <div class="w-full swiper-slide">
                        <div class="grid grid-cols-4 gap-3.5 w-full">
                            @foreach ($genreChunk as $genre)
                            <a href="{{ route('jelajahi', ['genre' => $genre->genre_id]) }}"
                               class="bg-[#181A22] rounded-xl p-4 hover:bg-[#1e2030] transition-colors group cursor-pointer
                                      {{ request('genre') == $genre->genre_id ? 'ring-2 ring-[#26bbff]' : '' }}">
                                {{-- Ambil 3 cover game dari genre ini --}}
                                @php
                                    $genreGames = $genre->games()
                                        ->where('is_active', true)
                                        ->whereNotNull('cover_image_url')
                                        ->limit(3)->get();
                                @endphp
                                <div class="relative h-36 mb-2">
                                    @foreach ($genreGames as $gi => $gGame)
                                    <img src="{{ $gGame->cover_image_url }}"
                                         class="absolute top-0 w-24 h-32 rounded-lg object-cover
                                                {{ $gi === 0 ? 'left-0 z-10' : ($gi === 1 ? 'left-8 z-20' : 'left-16 z-10') }}
                                                shadow-lg"
                                         onerror="this.src='https://placehold.co/96x128/1a1a22/ffffff?text=?'">
                                    @endforeach
                                    @if ($genreGames->isEmpty())
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-gray-600 text-xs">Belum ada game</span>
                                    </div>
                                    @endif
                                </div>
                                <h2 class="text-white text-sm font-medium text-center group-hover:text-[#26bbff] transition-colors mt-1">
                                    {{ $genre->name }}
                                </h2>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ---- Toolbar Sort + Filter aktif ---- --}}
            <div class="flex items-center gap-4 text-sm flex-wrap">
                <span class="text-zinc-400">Tampilkan:</span>

                {{-- Sort dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center gap-2 hover:text-white/80 transition-colors">
                        <span>
                            @switch(request('sort', 'newest'))
                                @case('price_asc')  Harga Terendah @break
                                @case('price_desc') Harga Tertinggi @break
                                @case('rating')     Rating Terbaik @break
                                @case('name')       Nama A-Z @break
                                @default            Rilisan Terbaru
                            @endswitch
                        </span>
                        <svg viewBox="0 0 30 30" fill="currentColor" class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''">
                            <path d="M18.707 9.707L12 16.414 5.293 9.707l1.414-1.414L12 13.586l5.293-5.293 1.414 1.414z"/>
                        </svg>
                    </button>
                    <div x-show="open" @click.outside="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute top-8 left-0 bg-[#1A1A22] border border-white/10 rounded-xl shadow-xl z-30 w-44 py-2">
                        @foreach ([
                            'newest'     => 'Rilisan Terbaru',
                            'price_asc'  => 'Harga Terendah',
                            'price_desc' => 'Harga Tertinggi',
                            'rating'     => 'Rating Terbaik',
                            'name'       => 'Nama A-Z',
                        ] as $val => $label)
                        <a href="{{ route('jelajahi', array_merge(request()->except('sort', 'page'), ['sort' => $val])) }}"
                           class="block px-4 py-2 text-sm hover:bg-white/10 transition-colors
                                  {{ request('sort', 'newest') === $val ? 'text-[#26bbff] font-semibold' : 'text-zinc-300' }}">
                            {{ $label }}
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Chip filter aktif --}}
                @if(request('type'))
                <span class="bg-zinc-700 px-3 py-1 rounded-full flex items-center gap-2 text-xs">
                    {{ ucfirst(str_replace('_', ' ', request('type'))) }}
                    <a href="{{ route('jelajahi', request()->except('type', 'page')) }}">
                        <svg viewBox="0 0 14 14" fill="currentColor" class="w-2.5 h-2.5">
                            <path d="M14 1.41L12.59 0 7 5.59 1.41 0 0 1.41 5.59 7 0 12.59 1.41 14 7 8.41 12.59 14 14 12.59 8.41 7z"/>
                        </svg>
                    </a>
                </span>
                @endif
                @if(request('genre'))
                @php $activeGenre = $genres->firstWhere('genre_id', request('genre')); @endphp
                @if($activeGenre)
                <span class="bg-zinc-700 px-3 py-1 rounded-full flex items-center gap-2 text-xs">
                    {{ $activeGenre->name }}
                    <a href="{{ route('jelajahi', request()->except('genre', 'page')) }}">
                        <svg viewBox="0 0 14 14" fill="currentColor" class="w-2.5 h-2.5">
                            <path d="M14 1.41L12.59 0 7 5.59 1.41 0 0 1.41 5.59 7 0 12.59 1.41 14 7 8.41 12.59 14 14 12.59 8.41 7z"/>
                        </svg>
                    </a>
                </span>
                @endif
                @endif
                @if(request('platform'))
                @php $activePlatform = $platforms->firstWhere('platform_id', request('platform')); @endphp
                @if($activePlatform)
                <span class="bg-zinc-700 px-3 py-1 rounded-full flex items-center gap-2 text-xs">
                    {{ $activePlatform->platform }}
                    <a href="{{ route('jelajahi', request()->except('platform', 'page')) }}">
                        <svg viewBox="0 0 14 14" fill="currentColor" class="w-2.5 h-2.5">
                            <path d="M14 1.41L12.59 0 7 5.59 1.41 0 0 1.41 5.59 7 0 12.59 1.41 14 7 8.41 12.59 14 14 12.59 8.41 7z"/>
                        </svg>
                    </a>
                </span>
                @endif
                @endif
                @if(request('price'))
                <span class="bg-zinc-700 px-3 py-1 rounded-full flex items-center gap-2 text-xs">
                    {{ ['free'=>'Gratis','discount'=>'Diskon','under150'=>'< Rp 150rb','under300'=>'< Rp 300rb'][request('price')] ?? request('price') }}
                    <a href="{{ route('jelajahi', request()->except('price', 'page')) }}">
                        <svg viewBox="0 0 14 14" fill="currentColor" class="w-2.5 h-2.5">
                            <path d="M14 1.41L12.59 0 7 5.59 1.41 0 0 1.41 5.59 7 0 12.59 1.41 14 7 8.41 12.59 14 14 12.59 8.41 7z"/>
                        </svg>
                    </a>
                </span>
                @endif

                {{-- Total hasil --}}
                <span class="text-zinc-500 ml-auto text-xs">{{ $games->total() }} game ditemukan</span>
            </div>

            {{-- ---- Grid Game dari Database ---- --}}
            @if ($games->isNotEmpty())
            <div class="grid grid-cols-4 gap-4">
                @foreach ($games as $game)
                <a href="{{ route('game.show', $game->game_id) }}"
                   class="flex flex-col group cursor-pointer">

                    {{-- Cover image --}}
                    <div class="relative rounded-lg overflow-hidden h-50">
                        <img src="{{ $game->cover_image_url }}"
                             alt="{{ $game->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                             onerror="this.src='https://placehold.co/360x480/1a1a22/ffffff?text=?'">

                        {{-- Info badge (First_Run, Now_On_Epic, Trial_Available) --}}
                        @if ($game->info)
                        <span class="absolute top-2 left-2 bg-[#26bbff] text-black text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wider">
                            {{ $game->info_label }}
                        </span>
                        @endif

                        {{-- Wishlist button (hover) --}}
                        @auth
                        <button class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity
                                       bg-black/50 hover:bg-black/70 p-1.5 rounded-full"
                                title="Tambah ke Wishlist"
                                onclick="event.preventDefault(); /* TODO: wishlist AJAX */">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-4 h-4">
                                <path d="M4.25 4.5A2.25 2.25 0 0 1 6.5 2.25h11a2.25 2.25 0 0 1 2.25 2.25V21a.75.75 0 0 1-1.238.57L12 15.987l-6.512 5.581A.75.75 0 0 1 4.25 21z" stroke-width="1.5" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        @endauth
                    </div>

                    {{-- Info teks --}}
                    <p class="mt-2 text-[11px] text-zinc-400 capitalize">
                        {{ str_replace('_', ' ', $game->game_type) }}
                    </p>
                    <h3 class="mt-0.5 text-sm font-semibold line-clamp-2 group-hover:text-white/80 transition-colors">
                        {{ $game->title }}
                    </h3>

                    {{-- Platform badges --}}
                    @if($game->relationLoaded('platforms') && $game->platforms->isNotEmpty())
                    <div class="flex gap-1 mt-1 flex-wrap">
                        @foreach($game->platforms->take(3) as $pl)
                        <span class="text-zinc-500 text-[10px]">{{ $pl->platform }}</span>
                        @endforeach
                    </div>
                    @endif

                    {{-- Harga --}}
                    <div class="mt-1.5 flex items-center gap-1.5 flex-wrap">
                        @if ($game->base_price == 0)
                            <span class="text-green-400 text-sm font-medium">Gratis</span>
                        @elseif ($game->discount_pct > 0)
                            <span class="bg-[#26bbff] text-black text-xs px-2 py-0.5 rounded-lg font-bold">-{{ $game->discount_pct }}%</span>
                            <s class="text-xs text-zinc-500">Rp {{ number_format($game->base_price, 0, ',', '.') }}</s>
                            <span class="text-sm font-medium">Rp {{ number_format($game->final_price, 0, ',', '.') }}</span>
                        @else
                            <span class="text-sm font-medium">Rp {{ number_format($game->base_price, 0, ',', '.') }}</span>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>

            {{-- ---- Pagination ---- --}}
            <div class="mt-8 flex items-center justify-center gap-2">
                {{-- Previous --}}
                @if ($games->onFirstPage())
                    <span class="py-2 px-3 rounded-lg bg-white/5 text-zinc-600 cursor-not-allowed text-sm">←</span>
                @else
                    <a href="{{ $games->previousPageUrl() }}"
                       class="py-2 px-3 rounded-lg bg-white/10 hover:bg-white/20 transition-colors text-sm">←</a>
                @endif

                {{-- Page numbers --}}
                @foreach ($games->getUrlRange(max(1, $games->currentPage()-2), min($games->lastPage(), $games->currentPage()+2)) as $page => $url)
                <a href="{{ $url }}"
                   class="py-2 px-3.5 rounded-lg text-sm transition-colors
                          {{ $page == $games->currentPage()
                             ? 'bg-[#26bbff] text-black font-bold'
                             : 'bg-white/10 hover:bg-white/20' }}">
                    {{ $page }}
                </a>
                @endforeach

                {{-- Next --}}
                @if ($games->hasMorePages())
                    <a href="{{ $games->nextPageUrl() }}"
                       class="py-2 px-3 rounded-lg bg-white/10 hover:bg-white/20 transition-colors text-sm">→</a>
                @else
                    <span class="py-2 px-3 rounded-lg bg-white/5 text-zinc-600 cursor-not-allowed text-sm">→</span>
                @endif
            </div>

            @else
            {{-- State kosong --}}
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <svg viewBox="0 0 64 64" class="w-16 h-16 text-zinc-700 mb-4" fill="none" stroke="currentColor">
                    <circle cx="32" cy="32" r="28" stroke-width="2"/>
                    <path d="M20 32h24M32 20v24" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <p class="text-zinc-400 text-lg font-semibold">Tidak ada game ditemukan</p>
                <p class="text-zinc-600 text-sm mt-1">Coba ubah atau hapus filter yang aktif</p>
                <a href="{{ route('jelajahi') }}"
                   class="mt-4 px-5 py-2 bg-white/10 hover:bg-white/20 rounded-xl text-sm transition-colors">
                    Reset Filter
                </a>
            </div>
            @endif

        </div>

        {{-- ===== KANAN: SIDEBAR FILTER (Alpine.js) ===== --}}
        <div class="w-72 shrink-0 bg-[#12131A] rounded-xl p-6 h-fit self-start text-white sticky top-24"
             x-data="{
                hargaOpen: true,
                genreOpen: false,
                platformOpen: false,
                tipeOpen: true,
             }">

            {{-- Header --}}
            <div class="flex justify-between items-center mb-5">
                <h2 class="font-semibold text-sm">
                    Filter
                    @php $activeFilters = collect(['type','genre','platform','price'])->filter(fn($k) => request($k))->count(); @endphp
                    @if ($activeFilters > 0)
                    <span class="ml-1 bg-[#26bbff] text-black text-xs px-1.5 py-0.5 rounded-full font-bold">{{ $activeFilters }}</span>
                    @endif
                </h2>
                @if ($activeFilters > 0)
                <a href="{{ route('jelajahi') }}" class="text-[#26bbff] text-xs hover:text-white transition-colors">Reset</a>
                @endif
            </div>

            {{-- Search keyword --}}
            <form action="{{ route('jelajahi') }}" method="GET">
                <input type="text" name="q" placeholder="Kata Kunci"
                       value="{{ request('q') }}"
                       class="w-full bg-[#24242c] rounded-xl px-4 py-2.5 text-xs mb-5 outline-none border border-transparent focus:border-white/20 transition-colors">
                @foreach(request()->except('q', 'page') as $k => $v)
                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                @endforeach
            </form>

            <div class="space-y-1 text-sm">

                {{-- HARGA --}}
                <div class="border-b border-zinc-800 pb-4">
                    <button @click="hargaOpen = !hargaOpen"
                            class="flex justify-between items-center w-full py-2 hover:text-white/80 transition-colors">
                        <span class="font-medium">Harga</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="hargaOpen ? 'rotate-180' : ''" viewBox="0 0 30 30" fill="currentColor">
                            <path d="M18.707 9.707L12 16.414 5.293 9.707l1.414-1.414L12 13.586l5.293-5.293 1.414 1.414z"/>
                        </svg>
                    </button>
                    <div x-show="hargaOpen" x-transition class="mt-3 space-y-2.5 text-zinc-300">
                        @foreach ([
                            'free'     => 'Gratis',
                            'discount' => 'Sedang Diskon',
                            'under150' => 'Di bawah Rp 150.000',
                            'under300' => 'Di bawah Rp 300.000',
                        ] as $val => $label)
                        <a href="{{ route('jelajahi', array_merge(request()->except('price','page'), request('price') === $val ? [] : ['price' => $val])) }}"
                           class="flex items-center gap-3 group cursor-pointer hover:text-white transition-colors">
                            <div class="w-4 h-4 rounded border flex items-center justify-center shrink-0
                                        {{ request('price') === $val ? 'bg-[#26bbff] border-[#26bbff]' : 'border-zinc-600 group-hover:border-white' }}">
                                @if(request('price') === $val)
                                <svg viewBox="0 0 12 12" class="w-3 h-3" fill="black"><path d="M10 3L5 8.5 2 5.5"/></svg>
                                @endif
                            </div>
                            <span class="text-xs">{{ $label }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- GENRE --}}
                @if($genres->isNotEmpty())
                <div class="border-b border-zinc-800 pb-4">
                    <button @click="genreOpen = !genreOpen"
                            class="flex justify-between items-center w-full py-2 hover:text-white/80 transition-colors">
                        <span class="font-medium">Genre</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="genreOpen ? 'rotate-180' : ''" viewBox="0 0 30 30" fill="currentColor">
                            <path d="M18.707 9.707L12 16.414 5.293 9.707l1.414-1.414L12 13.586l5.293-5.293 1.414 1.414z"/>
                        </svg>
                    </button>
                    <div x-show="genreOpen" x-transition class="mt-3 space-y-2.5 text-zinc-300">
                        @foreach ($genres as $genre)
                        <a href="{{ route('jelajahi', array_merge(request()->except('genre','page'), request('genre') == $genre->genre_id ? [] : ['genre' => $genre->genre_id])) }}"
                           class="flex items-center gap-3 group cursor-pointer hover:text-white transition-colors">
                            <div class="w-4 h-4 rounded border flex items-center justify-center shrink-0
                                        {{ request('genre') == $genre->genre_id ? 'bg-[#26bbff] border-[#26bbff]' : 'border-zinc-600 group-hover:border-white' }}">
                                @if(request('genre') == $genre->genre_id)
                                <svg viewBox="0 0 12 12" class="w-3 h-3" fill="black"><path d="M10 3L5 8.5 2 5.5"/></svg>
                                @endif
                            </div>
                            <span class="text-xs">{{ $genre->name }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- PLATFORM --}}
                @if($platforms->isNotEmpty())
                <div class="border-b border-zinc-800 pb-4">
                    <button @click="platformOpen = !platformOpen"
                            class="flex justify-between items-center w-full py-2 hover:text-white/80 transition-colors">
                        <span class="font-medium">Platform</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="platformOpen ? 'rotate-180' : ''" viewBox="0 0 30 30" fill="currentColor">
                            <path d="M18.707 9.707L12 16.414 5.293 9.707l1.414-1.414L12 13.586l5.293-5.293 1.414 1.414z"/>
                        </svg>
                    </button>
                    <div x-show="platformOpen" x-transition class="mt-3 space-y-2.5 text-zinc-300">
                        @foreach ($platforms as $pl)
                        <a href="{{ route('jelajahi', array_merge(request()->except('platform','page'), request('platform') == $pl->platform_id ? [] : ['platform' => $pl->platform_id])) }}"
                           class="flex items-center gap-3 group cursor-pointer hover:text-white transition-colors">
                            <div class="w-4 h-4 rounded border flex items-center justify-center shrink-0
                                        {{ request('platform') == $pl->platform_id ? 'bg-[#26bbff] border-[#26bbff]' : 'border-zinc-600 group-hover:border-white' }}">
                                @if(request('platform') == $pl->platform_id)
                                <svg viewBox="0 0 12 12" class="w-3 h-3" fill="black"><path d="M10 3L5 8.5 2 5.5"/></svg>
                                @endif
                            </div>
                            <span class="text-xs">{{ $pl->platform }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- TIPE GAME --}}
                <div class="pb-4">
                    <button @click="tipeOpen = !tipeOpen"
                            class="flex justify-between items-center w-full py-2 hover:text-white/80 transition-colors">
                        <span class="font-medium">Tipe</span>
                        <svg class="w-4 h-4 transition-transform duration-200" :class="tipeOpen ? 'rotate-180' : ''" viewBox="0 0 30 30" fill="currentColor">
                            <path d="M18.707 9.707L12 16.414 5.293 9.707l1.414-1.414L12 13.586l5.293-5.293 1.414 1.414z"/>
                        </svg>
                    </button>
                    <div x-show="tipeOpen" x-transition class="mt-3 space-y-2.5 text-zinc-300">
                        @foreach ([
                            'base_game'  => 'Game',
                            'addon'      => 'Add-On Game',
                            'bundle'     => 'Bundel Game',
                            'demo'       => 'Demo Game',
                            'edition'    => 'Edisi Game',
                            'aplikasi'   => 'Aplikasi',
                            'editor'     => 'Editor',
                            'experience' => 'Pengalaman',
                        ] as $val => $label)
                        <a href="{{ route('jelajahi', array_merge(request()->except('type','page'), request('type') === $val ? [] : ['type' => $val])) }}"
                           class="flex items-center gap-3 group cursor-pointer hover:text-white transition-colors">
                            <div class="w-4 h-4 rounded border flex items-center justify-center shrink-0
                                        {{ request('type') === $val ? 'bg-[#26bbff] border-[#26bbff]' : 'border-zinc-600 group-hover:border-white' }}">
                                @if(request('type') === $val)
                                <svg viewBox="0 0 12 12" class="w-3 h-3" fill="black"><path d="M10 3L5 8.5 2 5.5"/></svg>
                                @endif
                            </div>
                            <span class="text-xs">{{ $label }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- Swiper JS --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Swiper !== 'undefined') {
            new Swiper('.gameSwiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.swiper-btn-next',
                    prevEl: '.swiper-btn-prev',
                }
            });
        }
    });
    </script>

</body>
</html>
