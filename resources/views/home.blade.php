<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Epic Games Store</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="antialiased pb-20 min-w-full max-w-full w-full flex flex-col bg-background text-primary">

    {{-- HEADER --}}
    <header class="text-primary w-full min-h-18 bg-white/1 flex items-center justify-center px-5">
        <div class="w-full h-full flex items-center justify-start gap-8">
            <div class="h-full flex items-center justify-start gap-9">
                <a href="{{ route('home') }}" class="flex w-fit cursor-pointer group">
                    <img src="/img/logo_ph.png" alt="Logo" class="h-8.5">
                    <span class="flex items-center group-hover:rotate-180 transition-transform duration-150">
                        <svg viewBox="0 0 24 24" class="w-3 h-3" fill="currentColor">
                            <path d="M18.47 8.97a.75.75 0 1 1 1.06 1.06L12 17.56l-7.53-7.53a.75.75 0 1 1 1.06-1.06L12 15.44z"></path>
                        </svg>
                    </span>
                </a>
                <b class="font-black text-lg">STORE</b>
            </div>
            <nav class="w-fit h-full flex items-center gap-8">
                <a href="#" class="text-sm font-medium hover:text-white/70 transition-colors">Dukungan</a>
                <a href="#" class="text-sm font-medium hover:text-white/70 transition-colors">Distribusi</a>
            </nav>
        </div>
        <div class="w-full h-full flex items-center justify-end gap-5">
            @auth
                <a href="{{ route('library.index') }}" class="font-semibold text-sm bg-white/15 hover:bg-white/30 px-3 py-1.5 rounded-md tracking-wide transition-colors">Library</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="font-semibold text-sm bg-white/15 hover:bg-white/30 px-3 py-1.5 rounded-md tracking-wide transition-colors">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="font-semibold text-sm bg-white/15 hover:bg-white/30 px-3 py-1.5 rounded-md tracking-wide transition-colors">Masuk</a>
                <a href="{{ route('register') }}" class="font-semibold text-sm bg-[#26bbff] text-black px-3 py-1.5 rounded-md tracking-wide hover:bg-[#56cbff] transition-colors">Daftar</a>
            @endauth
        </div>
    </header>

    {{-- STICKY NAV --}}
    <div class="flex items-center gap-10 h-20 sticky top-0 px-40 py-8 bg-background/95 backdrop-blur-sm z-50 border-b border-white/5">
        <form action="{{ route('jelajahi') }}" method="GET">
            <input name="q" class="bg-[#1A1A22] rounded-full px-5 py-2.5 w-60 text-gray-300 text-sm outline-none border border-transparent focus:border-white/20 transition-colors"
                placeholder="Cari di toko" value="{{ request('q') }}" />
        </form>
        <div class="flex gap-8 text-sm">
            <a href="{{ route('home') }}" class="font-semibold border-b-2 border-white pb-1">Temukan</a>
            <a href="{{ route('jelajahi') }}" class="text-gray-400 hover:text-white transition-colors">Jelajahi</a>
            <a href="{{ route('news.index') }}" class="text-gray-400 hover:text-white transition-colors">Berita</a>
        </div>
    </div>

    {{-- HERO CAROUSEL + SIDEBAR --}}
    @if ($featuredGames->isNotEmpty())
    <div class="px-40 mt-4 max-w-full w-full flex items-start gap-x-5">
        <div class="swiper mySwiper flex-1">
            <div class="swiper-wrapper">
                @foreach ($featuredGames as $game)
                <div class="swiper-slide max-h-fit relative group">
                    <a href="{{ route('game.show', $game->game_id) }}">
                        <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}"
                            class="w-full aspect-video object-cover rounded-2xl transition-transform duration-500 group-hover:scale-[1.01]"
                            onerror="this.src='https://placehold.co/1280x720/1a1a22/ffffff?text=Game'">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent rounded-2xl"></div>
                        <div class="h-full w-full absolute top-0 left-0 flex flex-col items-start justify-end p-10 gap-5">
                            @if ($game->info)
                            <span class="bg-[#26bbff] text-black text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                {{ $game->info_label }}
                            </span>
                            @endif
                            <div class="text-white max-w-[65%]">
                                <h3 class="text-2xl font-bold leading-tight drop-shadow-lg">{{ $game->title }}</h3>
                                @if ($game->main_desc)
                                <p class="text-sm text-white/80 mt-2 line-clamp-2">{{ $game->main_desc }}</p>
                                @endif
                            </div>
                            <div class="flex flex-col items-start gap-3">
                                <div class="text-white flex items-center gap-2 text-sm">
                                    @if ($game->base_price == 0)
                                        <span class="text-green-400 font-bold text-base">GRATIS</span>
                                    @elseif ($game->discount_pct > 0)
                                        <span class="bg-[#26bbff] px-2.5 py-0.5 text-xs text-black rounded-xl font-bold">-{{ $game->discount_pct }}%</span>
                                        <s class="opacity-60">Rp {{ number_format($game->base_price, 0, ',', '.') }}</s>
                                        <span class="font-semibold">Rp {{ number_format($game->final_price, 0, ',', '.') }}</span>
                                    @else
                                        <span class="font-semibold text-base">Rp {{ number_format($game->base_price, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                                <div class="flex gap-3">
                                    <span class="font-semibold text-sm bg-primary text-background px-6 py-3 rounded-xl hover:bg-white/90 transition-colors">
                                        @if ($game->base_price == 0) Ambil Gratis @else Pesan Sekarang @endif
                                    </span>
                                    <span class="bg-white/15 hover:bg-white/30 w-12 py-3 rounded-xl flex items-center justify-center transition-colors">
                                        <svg class="fill-current w-5" viewBox="0 0 24 24">
                                            <path d="M4.25 4.5A2.25 2.25 0 0 1 6.5 2.25h11a2.25 2.25 0 0 1 2.25 2.25V21a.75.75 0 0 1-1.238.57L12 15.987l-6.512 5.581A.75.75 0 0 1 4.25 21zm2.25-.75a.75.75 0 0 0-.75.75v14.87l5.762-4.94a.75.75 0 0 1 .976 0l5.762 4.94V4.5a.75.75 0 0 0-.75-.75z" clip-rule="evenodd" fill-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Sidebar thumbnail list --}}
        <div class="shrink-0 w-[20%] flex flex-col gap-1 pt-1">
            @foreach ($featuredGames as $game)
            <a href="{{ route('game.show', $game->game_id) }}"
               class="px-2 py-2 rounded-lg flex items-center gap-3 hover:bg-white/10 transition-colors group">
                <img class="w-10 h-14 object-cover rounded-md shrink-0 ring-1 ring-white/10"
                     src="{{ $game->cover_image_url }}" alt="{{ $game->title }}"
                     onerror="this.src='https://placehold.co/96x128/1a1a22/ffffff?text=?'">
                <p class="text-sm font-medium line-clamp-2 group-hover:text-white/80 transition-colors">{{ $game->title }}</p>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- SECTION: TEMUKAN SESUATU YANG BARU --}}
    @if ($newGames->isNotEmpty())
    <div class="px-40 mt-12 max-w-full w-full">
        <div class="swiper gameSwiper w-full overflow-hidden">
            <div class="flex w-full items-center justify-between py-4 mb-2">
                <h2 class="text-xl font-bold">Temukan Sesuatu yang Baru</h2>
                <div class="flex gap-2">
                    <button class="swiper-btn-prev py-2 px-[11px] cursor-pointer rounded-full bg-white/20 hover:bg-white/30 rotate-180 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-2" viewBox="0 0 5 9"><path stroke="currentColor" d="M1 1l3 3.5L1 8" fill="none" fill-rule="evenodd"></path></svg>
                    </button>
                    <button class="swiper-btn-next py-2 px-[11px] cursor-pointer rounded-full bg-white/20 hover:bg-white/30 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-2" viewBox="0 0 5 9"><path stroke="currentColor" d="M1 1l3 3.5L1 8" fill="none" fill-rule="evenodd"></path></svg>
                    </button>
                </div>
            </div>
            <div class="w-full swiper-wrapper">
                @foreach ($newGames->chunk(5) as $chunk)
                <div class="w-full swiper-slide">
                    <div class="grid grid-cols-5 gap-4 w-full">
                        @foreach ($chunk as $game)
                        <a href="{{ route('game.show', $game->game_id) }}" class="flex flex-col group cursor-pointer">
                            <div class="h-55 rounded-lg overflow-hidden">
                                <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    onerror="this.src='https://placehold.co/360x480/1a1a22/ffffff?text=?'">
                            </div>
                            <p class="mt-2 text-xs text-gray-400 capitalize">{{ str_replace('_', ' ', $game->game_type) }}</p>
                            <h5 class="mt-1 text-sm font-semibold line-clamp-2 group-hover:text-white/80 transition-colors">{{ $game->title }}</h5>
                            <div class="mt-1 flex items-center gap-1.5 flex-wrap">
                                @if ($game->discount_pct > 0)
                                    <span class="bg-[#26bbff] px-2 py-0.5 text-xs text-black rounded-lg font-bold">-{{ $game->discount_pct }}%</span>
                                    <s class="text-xs text-gray-500">Rp {{ number_format($game->base_price, 0, ',', '.') }}</s>
                                    <span class="text-sm font-medium">Rp {{ number_format($game->final_price, 0, ',', '.') }}</span>
                                @elseif ($game->base_price == 0)
                                    <span class="text-green-400 text-sm font-medium">Gratis</span>
                                @else
                                    <span class="text-sm font-medium">Rp {{ number_format($game->base_price, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Promo banner --}}
        <div class="mt-10 relative rounded-xl overflow-hidden h-52">
            <img src="https://cdn2.unrealengine.com/uefn-40-00-akitaexemplars-egs-twifbanner-desktop-2912x800-2912x800-13ef7f29bc9f.jpg?resize=1&w=1920&h=1080&quality=medium"
                alt="Promo" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center gap-4">
                <h3 class="text-white text-2xl font-bold">Minggu Ini di Fortnite</h3>
                <p class="text-white/80">Temukan game baru dan penawaran eksklusif!</p>
                <a href="{{ route('jelajahi') }}" class="font-semibold text-sm bg-primary text-background px-6 py-3 rounded-xl hover:bg-white/90 transition-colors">
                    Temukan Sekarang
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- SECTION: DISKON UNGGULAN --}}
    @if ($discountedGames->isNotEmpty())
    <div class="px-40 mt-12 max-w-full w-full">
        <div class="swiper gameSwiper2 w-full overflow-hidden">
            <div class="flex w-full items-center justify-between py-4 mb-2">
                <h2 class="text-xl font-bold">Diskon Unggulan</h2>
                <div class="flex gap-2">
                    <button class="swiper2-btn-prev py-2 px-[11px] cursor-pointer rounded-full bg-white/20 hover:bg-white/30 rotate-180 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-2" viewBox="0 0 5 9"><path stroke="currentColor" d="M1 1l3 3.5L1 8" fill="none" fill-rule="evenodd"></path></svg>
                    </button>
                    <button class="swiper2-btn-next py-2 px-[11px] cursor-pointer rounded-full bg-white/20 hover:bg-white/30 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-2" viewBox="0 0 5 9"><path stroke="currentColor" d="M1 1l3 3.5L1 8" fill="none" fill-rule="evenodd"></path></svg>
                    </button>
                </div>
            </div>
            <div class="w-full swiper-wrapper">
                @foreach ($discountedGames->chunk(5) as $chunk)
                <div class="w-full swiper-slide">
                    <div class="grid grid-cols-5 gap-4 w-full">
                        @foreach ($chunk as $game)
                        <a href="{{ route('game.show', $game->game_id) }}" class="flex flex-col group cursor-pointer">
                            <div class="h-55 rounded-lg overflow-hidden">
                                <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    onerror="this.src='https://placehold.co/360x480/1a1a22/ffffff?text=?'">
                            </div>
                            <p class="mt-2 text-xs text-gray-400 capitalize">{{ str_replace('_', ' ', $game->game_type) }}</p>
                            <h5 class="mt-1 text-sm font-semibold line-clamp-2 group-hover:text-white/80 transition-colors">{{ $game->title }}</h5>
                            <div class="mt-1 flex items-center gap-1.5 flex-wrap">
                                @if ($game->discount_pct > 0)
                                    <span class="bg-[#26bbff] px-2 py-0.5 text-xs text-black rounded-lg font-bold">-{{ $game->discount_pct }}%</span>
                                    <s class="text-xs text-gray-500">Rp {{ number_format($game->base_price, 0, ',', '.') }}</s>
                                    <span class="text-sm font-medium">Rp {{ number_format($game->final_price, 0, ',', '.') }}</span>
                                @elseif ($game->base_price == 0)
                                    <span class="text-green-400 text-sm font-medium">Gratis</span>
                                @else
                                    <span class="text-sm font-medium">Rp {{ number_format($game->base_price, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- SECTION: PROMO TILES --}}
    <div class="px-40 mt-12 max-w-full w-full">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold">Promo Minggu Ini</h2>
            <a href="{{ route('jelajahi', ['price' => 'discount']) }}" class="text-sm text-[#26bbff] hover:text-white transition-colors">Lihat Semua</a>
        </div>
        <div class="grid grid-cols-3 gap-5">
            @foreach ($discountedGames->take(2) as $game)
            <a href="{{ route('game.show', $game->game_id) }}" class="flex flex-col group cursor-pointer">
                <div class="h-48 rounded-xl overflow-hidden">
                    <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        onerror="this.src='https://placehold.co/854x480/1a1a22/ffffff?text=Game'">
                </div>
                <h5 class="mt-4 text-lg font-bold line-clamp-2 group-hover:text-white/80 transition-colors">{{ $game->title }}</h5>
                <div class="flex items-center gap-2 mt-2">
                    @if ($game->discount_pct > 0)
                        <span class="bg-[#26bbff] px-2 py-0.5 text-xs text-black rounded-lg font-bold">-{{ $game->discount_pct }}%</span>
                        <s class="text-sm text-gray-500">Rp {{ number_format($game->base_price, 0, ',', '.') }}</s>
                        <span class="font-medium">Rp {{ number_format($game->final_price, 0, ',', '.') }}</span>
                    @else
                        <span class="font-medium">Rp {{ number_format($game->base_price, 0, ',', '.') }}</span>
                    @endif
                </div>
            </a>
            @endforeach

            <div class="flex flex-col">
                <div class="h-48 relative rounded-xl overflow-hidden">
                    <img src="https://cdn2.unrealengine.com/id-sales-specials-dotw-breaker-asset-1920x1080-e7255cdf8c3c.jpg?resize=1&w=854&h=480&quality=medium"
                        alt="Promo" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                        <a href="{{ route('jelajahi', ['price' => 'discount']) }}" class="font-semibold text-sm bg-white/20 hover:bg-white/30 border border-white/30 px-5 py-2.5 rounded-xl transition-colors">
                            Jelajahi Semua Promo
                        </a>
                    </div>
                </div>
                <h5 class="mt-4 text-lg font-bold">Lihat semua promo<br>minggu ini</h5>
                <a href="{{ route('jelajahi', ['price' => 'discount']) }}" class="mt-2 font-semibold text-sm bg-white/15 hover:bg-white/30 px-4 py-2 rounded-md w-fit transition-colors">
                    Jelajahi
                </a>
            </div>
        </div>
    </div>

    {{-- SECTION: GAME GRATIS --}}
    @if ($freeGames->isNotEmpty())
    <div class="px-40 mt-12 max-w-full w-full">
        <div class="w-full bg-zinc-900/80 rounded-3xl p-8 border border-white/5">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <svg viewBox="0 0 32 32" class="fill-primary w-6 h-6">
                        <path d="M30.443 16.605H19.72v-3.46h10.724v3.46zm-2.075 12.308c0 .397-.576.838-1.402.838h-7.247V17.99h8.648v10.924zm-14.876.838h4.843v-18.68h-4.843v18.68zm-10.033-.838V17.99h8.649v11.762H4.861c-.826 0-1.402-.441-1.402-.838zM1.384 13.146h10.724v3.46H1.384v-3.46zm1.773-4.324c0-1.622 1.319-2.94 2.94-2.94 2.752 0 5.093 3.78 5.575 5.88H6.097a2.944 2.944 0 0 1-2.94-2.94zm22.573-2.94c1.621 0 2.94 1.318 2.94 2.94 0 1.621-1.319 2.94-2.94 2.94h-5.574c.481-2.1 2.822-5.88 5.574-5.88zm5.405 5.88h-2.244a4.304 4.304 0 0 0 1.163-2.94 4.329 4.329 0 0 0-4.324-4.325c-2.89 0-5.227 2.813-6.341 5.294a.686.686 0 0 0-.362-.105H12.8a.686.686 0 0 0-.362.105c-1.114-2.481-3.45-5.294-6.34-5.294a4.329 4.329 0 0 0-4.325 4.325c0 1.136.444 2.168 1.163 2.94H.692a.692.692 0 0 0-.692.692v4.843c0 .382.31.692.692.692h1.384v10.924c0 1.246 1.223 2.222 2.785 2.222h22.105c1.562 0 2.785-.976 2.785-2.222V17.99h1.384c.382 0 .692-.31.692-.692v-4.843a.692.692 0 0 0-.692-.692zM15.914 4.151c.381 0 .691-.31.691-.692V.692a.692.692 0 0 0-1.383 0v2.767c0 .382.31.692.692.692"></path>
                    </svg>
                    <h2 class="text-xl font-bold">Game Gratis</h2>
                </div>
                <a href="{{ route('jelajahi', ['price' => 'free']) }}" class="border border-zinc-600 text-sm px-5 py-2.5 rounded-xl hover:bg-zinc-800 transition-colors">
                    Lihat Selengkapnya
                </a>
            </div>
            <div class="grid grid-cols-4 gap-6">
                @foreach ($freeGames as $game)
                <a href="{{ route('game.show', $game->game_id) }}" class="flex flex-col group cursor-pointer">
                    <div class="relative rounded-xl overflow-hidden h-64">
                        <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            onerror="this.src='https://placehold.co/360x480/1a1a22/ffffff?text=?'">
                        <div class="absolute bottom-0 left-0 right-0 bg-sky-500 text-black text-center py-2 text-xs font-bold uppercase tracking-wide">
                            GRATIS SEKARANG
                        </div>
                    </div>
                    <h5 class="mt-4 font-bold line-clamp-2 group-hover:text-white/80 transition-colors">{{ $game->title }}</h5>
                    <p class="text-zinc-400 text-sm mt-1">Gratis Sekarang</p>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Swiper --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // Hero Carousel
        if (document.querySelector('.mySwiper')) {
            new Swiper('.mySwiper', {
                loop: true,
                autoplay: { delay: 5000, disableOnInteraction: false },
                effect: 'fade',
                fadeEffect: { crossFade: true },
                speed: 600,
            });
        }

        // Temukan Sesuatu yang Baru
        if (document.querySelector('.gameSwiper')) {
            new Swiper('.gameSwiper', {
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.swiper-btn-next',
                    prevEl: '.swiper-btn-prev',
                },
                speed: 400,
            });
        }

        // Diskon Unggulan
        if (document.querySelector('.gameSwiper2')) {
            new Swiper('.gameSwiper2', {
                slidesPerView: 1,
                spaceBetween: 20,
                navigation: {
                    nextEl: '.swiper2-btn-next',
                    prevEl: '.swiper2-btn-prev',
                },
                speed: 400,
            });
        }

    });
    </script>

</body>
</html>