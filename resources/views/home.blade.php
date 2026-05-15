<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    {{--
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" /> --}}
    <link
        href="https://fonts.bunny.net/css?family=inter:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="antialiased pb-14 min-h-[220vh] min-w-full max-w-full w-full flex flex-col">

    <header class=" text-primary w-full min-h-18 bg-white/1 flex items-center justify-center px-5">
        {{-- KIRI --}}
        <div class="w-full h-full flex items-center justify-start gap-8">
            <div class="h-full flex items-center justify-start gap-9">
                <button class="flex w-fit cursor-pointer group">
                    <img src="/img/logo_ph.png" alt="Logo Epic Games" class="h-8.5">
                    <span class="flex items-center group-hover:rotate-180 transition-transform duration-150">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="currentColor">
                            <path
                                d="M18.47 8.97a.75.75 0 1 1 1.06 1.06L12 17.56l-7.53-7.53a.75.75 0 1 1 1.06-1.06L12 15.44z">
                            </path>
                        </svg>
                    </span>
                </button>
                <b class="font-black text-lg">STORE</b>
            </div>
            <nav class="w-fit h-full flex items-center justify-start gap-8">
                <a href="#" class="h-full flex items-center justify-center text-sm font-medium">Dukungan</a>
                <div class="flex w-fit cursor-pointer group gap-1">
                    <a href="#" class="h-full flex items-center justify-center text-sm font-medium">Distribusi</a>
                    <span class="flex items-center group-hover:rotate-180 transition-transform duration-150">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="currentColor">
                            <path
                                d="M18.47 8.97a.75.75 0 1 1 1.06 1.06L12 17.56l-7.53-7.53a.75.75 0 1 1 1.06-1.06L12 15.44z">
                            </path>
                        </svg>
                    </span>
                </div>
            </nav>
        </div>

        {{-- KANAN --}}
        <div class="w-full h-full flex items-center justify-end gap-5">
            <button class="flex items-center cursor-pointer">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="fill-primary w-6 h-6">
                    <path
                        d="M12 2.25c5.385 0 9.75 4.365 9.75 9.75s-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12 6.615 2.25 12 2.25m-2.487 10.5c.065 2.116.4 3.991.894 5.36.277.766.59 1.334.899 1.696.311.364.548.444.694.444s.383-.08.694-.444c.31-.362.622-.93.899-1.697.494-1.368.83-3.243.894-5.359zm-5.727 0a8.25 8.25 0 0 0 5.772 7.132 9 9 0 0 1-.562-1.263c-.563-1.558-.919-3.613-.984-5.869zm12.202 0c-.065 2.256-.421 4.31-.984 5.87a9 9 0 0 1-.563 1.262 8.25 8.25 0 0 0 5.773-7.132zm-6.43-8.633a8.255 8.255 0 0 0-5.772 7.133h4.226c.065-2.256.421-4.31.984-5.87.165-.456.353-.882.562-1.263M12 3.75c-.146 0-.383.08-.694.444-.31.362-.622.93-.899 1.697-.494 1.368-.83 3.243-.894 5.359h4.974c-.065-2.116-.4-3.991-.894-5.36-.277-.766-.59-1.334-.899-1.696-.311-.364-.548-.444-.694-.444m2.441.367c.21.381.398.807.563 1.264.563 1.559.919 3.613.984 5.869h4.226a8.255 8.255 0 0 0-5.773-7.133">
                    </path>
                </svg>
            </button>
            <div class="flex items-end gap-2">
                <button
                    class="font-semibold text-sm bg-white/15 hover:bg-white/30 px-3 py-1.5 rounded-md tracking-wide cursor-pointer">Masuk</button>
                <button
                    class="font-semibold text-sm bg-[#26bbff] text-background px-3 py-1.5 rounded-md tracking-wide cursor-pointer">Unduh</button>
            </div>
        </div>
    </header>

    <div class="flex items-center gap-10 h-24 sticky top-0 px-40 py-8 bg-background z-100">
        <input class="bg-[#1A1A22] rounded-full px-5 py-2.5 w-56 text-gray-300 text-sm outline-none border-none"
            placeholder="Cari di toko" />

        <div class="flex gap-8 text-sm">
            <button class="font-semibold border-b-2 border-white pb-1">Temukan</button>
            <button class="text-gray-400 hover:text-white">Jelajahi</button>
            <button class="text-gray-400 hover:text-white">Berita</button>
        </div>
    </div>

    {{--
    https://cdn2.unrealengine.com/egs-lego-batman-lotdk-carousel-desktop-1920x1080-315911b634ee.jpg?resize=1&w=1280&h=720&quality=medium
    --}}
    <div class="px-40 h-min max-h-min max-w-full w-full flex items-center gap-x-5">
        <div class="swiper mySwiper flex-1">
            <div class="swiper-wrapper">

                <div class="swiper-slide max-h-fit relative">
                    <img src="https://cdn2.unrealengine.com/egs-lego-batman-lotdk-carousel-desktop-1920x1080-315911b634ee.jpg?resize=1&w=1280&h=720&quality=medium"
                        alt="Image 1" class="w-full aspect-video object-cover rounded-2xl">

                    <div class=" h-full w-full absolute top-0 left-0 flex flex-col items-start justify-end p-10 gap-8">
                        <div class="text-white max-w-[60%]">
                            <h3 class="text-xl font-bold">Game Title</h3>
                            <p class="text-sm">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Aut natus
                                fugiat quo recusandae dolore rerum nostrum alias accusamus error officiis!</p>
                        </div>
                        <div class="flex flex-col items-start gap-2">
                            <div class="text-white flex items-center gap-2 text-sm">
                                <span
                                    class="bg-[#26bbff] px-2.5 py-0.75 text-xs text-black rounded-xl font-medium">-10%</span>
                                <s class="opacity-70">Rp 19.999*</s>
                                <p class="">Rp 17.000</p>
                            </div>
                            <div class="flex gap-4">
                                <button
                                    class="font-semibold text-sm bg-primary text-background px-5.5 py-3 rounded-xl tracking-wide cursor-pointer">
                                    Pesan Sekarang
                                </button>
                                <button
                                    class=" bg-white/15 hover:bg-white/30 w-12 py-3 cursor-pointer rounded-xl flex items-center justify-center gap-2">
                                    <svg aria-hidden="true" class="fill-current w-5" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2.423A3.25 3.25 0 0 0 6.76 6.25H4.5A2.25 2.25 0 0 0 2.25 8.5v2c0 .78.397 1.467 1 1.871V19.5a2.25 2.25 0 0 0 2.25 2.25h13a2.25 2.25 0 0 0 2.25-2.25v-7.129c.603-.404 1-1.09 1-1.871v-2a2.25 2.25 0 0 0-2.25-2.25h-2.26A3.25 3.25 0 0 0 12 2.423m-2.5.327a1.75 1.75 0 1 0 0 3.5h1.75V4.5A1.75 1.75 0 0 0 9.5 2.75m3.25 5v3.5h6.75a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 0-.75-.75zm1.75-1.5a1.75 1.75 0 1 0-1.75-1.75v1.75zm-3.25 1.5H4.5a.75.75 0 0 0-.75.75v2c0 .414.336.75.75.75h6.75zm1.5 5h6.5v6.75a.75.75 0 0 1-.75.75h-5.75zm-1.5 0v7.5H5.5a.75.75 0 0 1-.75-.75v-6.75z"
                                            clip-rule="evenodd" fill-rule="evenodd"></path>
                                    </svg>
                                </button>
                                <button
                                    class=" bg-white/15 hover:bg-white/30 w-12 py-3 cursor-pointer rounded-xl flex items-center justify-center gap-2">
                                    <svg aria-hidden="true" class="fill-current w-5" width="24" height="24"
                                        viewBox="0 0 24 24" data-testid="empty-icon">
                                        <path
                                            d="M4.25 4.5A2.25 2.25 0 0 1 6.5 2.25h11a2.25 2.25 0 0 1 2.25 2.25V21a.75.75 0 0 1-1.238.57L12 15.987l-6.512 5.581A.75.75 0 0 1 4.25 21zm2.25-.75a.75.75 0 0 0-.75.75v14.87l5.762-4.94a.75.75 0 0 1 .976 0l5.762 4.94V4.5a.75.75 0 0 0-.75-.75z"
                                            clip-rule="evenodd" fill-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="swiper-slide max-h-fit relative">
                    <img src="https://cdn2.unrealengine.com/egs-subnautica-2-cover-story-carousel-mobile-1200x1600-a69810f80acc.png?resize=1&w=640&h=854&quality=medium"
                        alt="Image 1" class="w-full aspect-video object-cover rounded-2xl">

                    <div class=" h-full w-full absolute top-0 left-0 flex flex-col items-start justify-end p-10 gap-8">
                        <div class="text-white max-w-[60%]">
                            <h3 class="text-xl font-bold">Game Title</h3>
                            <p class="text-sm">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Aut natus
                                fugiat quo recusandae dolore rerum nostrum alias accusamus error officiis!</p>
                        </div>
                        <div class="flex flex-col items-start gap-2">
                            <div class="text-white flex items-center gap-2 text-sm">
                                <span
                                    class="bg-[#26bbff] px-2.5 py-0.75 text-xs text-black rounded-xl font-medium">-10%</span>
                                <s class="opacity-70">Rp 19.999*</s>
                                <p class="">Rp 17.000</p>
                            </div>
                            <div class="flex gap-4">
                                <button
                                    class="font-semibold text-sm bg-primary text-background px-5.5 py-3 rounded-xl tracking-wide cursor-pointer">
                                    Pesan Sekarang
                                </button>
                                <button
                                    class=" bg-white/15 hover:bg-white/30 w-12 py-3 cursor-pointer rounded-xl flex items-center justify-center gap-2">
                                    <svg aria-hidden="true" class="fill-current w-5" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2.423A3.25 3.25 0 0 0 6.76 6.25H4.5A2.25 2.25 0 0 0 2.25 8.5v2c0 .78.397 1.467 1 1.871V19.5a2.25 2.25 0 0 0 2.25 2.25h13a2.25 2.25 0 0 0 2.25-2.25v-7.129c.603-.404 1-1.09 1-1.871v-2a2.25 2.25 0 0 0-2.25-2.25h-2.26A3.25 3.25 0 0 0 12 2.423m-2.5.327a1.75 1.75 0 1 0 0 3.5h1.75V4.5A1.75 1.75 0 0 0 9.5 2.75m3.25 5v3.5h6.75a.75.75 0 0 0 .75-.75v-2a.75.75 0 0 0-.75-.75zm1.75-1.5a1.75 1.75 0 1 0-1.75-1.75v1.75zm-3.25 1.5H4.5a.75.75 0 0 0-.75.75v2c0 .414.336.75.75.75h6.75zm1.5 5h6.5v6.75a.75.75 0 0 1-.75.75h-5.75zm-1.5 0v7.5H5.5a.75.75 0 0 1-.75-.75v-6.75z"
                                            clip-rule="evenodd" fill-rule="evenodd"></path>
                                    </svg>
                                </button>
                                <button
                                    class=" bg-white/15 hover:bg-white/30 w-12 py-3 cursor-pointer rounded-xl flex items-center justify-center gap-2">
                                    <svg aria-hidden="true" class="fill-current w-5" width="24" height="24"
                                        viewBox="0 0 24 24" data-testid="empty-icon">
                                        <path
                                            d="M4.25 4.5A2.25 2.25 0 0 1 6.5 2.25h11a2.25 2.25 0 0 1 2.25 2.25V21a.75.75 0 0 1-1.238.57L12 15.987l-6.512 5.581A.75.75 0 0 1 4.25 21zm2.25-.75a.75.75 0 0 0-.75.75v14.87l5.762-4.94a.75.75 0 0 1 .976 0l5.762 4.94V4.5a.75.75 0 0 0-.75-.75z"
                                            clip-rule="evenodd" fill-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            {{-- <div class="swiper-pagination"></div> --}}

            {{-- <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div> --}}
        </div>

        <div class=" shrink-0 w-[20%] h-102 *:hover:bg-white/10 *:cursor-pointer rounded-lg">
            <div class="px-2 py-2 rounded-lg max-h-1/6 flex items-center gap-2 max-w-full">
                <img class="w-10 object-cover rounded-md"
                    src="https://cdn2.unrealengine.com/egs-lego-batman-lotdk-carousel-thumb-1200x1600-4b13307eacab.jpg?resize=1&w=96&h=128&quality=medium"
                    alt="">
                <p class="text-sm max-w-full overflow-clip font-medium">LEGO® Batman™: Legacy of the Dark Knight</p>
            </div>
            <div class="px-2 py-2 rounded-lg max-h-1/6 flex items-center gap-2 max-w-full">
                <img class="w-10 object-cover rounded-md"
                    src="https://cdn2.unrealengine.com/egs-subnautica-2-cover-story-carousel-thumb-1200x1600-9b93c28f2913.png?resize=1&w=96&h=128&quality=medium"
                    alt="">
                <p class="text-sm max-w-full overflow-clip font-medium">Subnautica 2</p>
            </div>
            <div class="px-2 py-2 rounded-lg max-h-1/6 flex items-center gap-2 max-w-full">
                <img class="w-10 object-cover rounded-md"
                    src="https://cdn2.unrealengine.com/egs-control-resonant-carousel-thumb-1200x1600-b5ef94b040bf.jpg?resize=1&w=96&h=128&quality=medium"
                    alt="">
                <p class="text-sm max-w-full overflow-clip font-medium">CONTROL Resonant</p>
            </div>
            <div class="px-2 py-2 rounded-lg max-h-1/6 flex items-center gap-2 max-w-full">
                <img class="w-10 object-cover rounded-md"
                    src="https://cdn2.unrealengine.com/egs-dead-as-disco-carousel-thumb-1200x1600-9b827ee8e44a.jpg?resize=1&w=96&h=128&quality=medium"
                    alt="">
                <p class="text-sm max-w-full overflow-clip font-medium">Dead as Disco</p>
            </div>
            <div class="px-2 py-2 rounded-lg max-h-1/6 flex items-center gap-2 max-w-full">
                <img class="w-10 object-cover rounded-md"
                    src="https://cdn2.unrealengine.com/egs-outbound-carousel-thumb-1200x1600-4c7de4d647fc.jpg?resize=1&w=96&h=128&quality=medium"
                    alt="">
                <p class="text-sm max-w-full overflow-clip font-medium">Outbound</p>
            </div>
            <div class="px-2 py-2 rounded-lg max-h-1/6 flex items-center gap-2 max-w-full">
                <img class="w-10 object-cover rounded-md"
                    src="https://cdn2.unrealengine.com/egs-battlefield-6-carousel-thumb-1200x1600-7c909c341e83.jpg?resize=1&w=96&h=128&quality=medium"
                    alt="">
                <p class="text-sm max-w-full overflow-clip font-medium">Battlefield 6 | REDSEC</p>
            </div>
        </div>
    </div>
    <div class="px-40 mt-10 h-min max-w-full w-full flex items-center gap-x-5">
        <div class="flex flex-col gap-4 max-w-full w-full">
            <div class=" flex flex-col swiper gameSwiper w-full items-center justify-between">
                <div class="flex w-full items-center justify-between py-4">
                    <h2 class="text-xl font-bold">Temukan Sesuatu yang Baru</h2>
                    <div class="flex h-full w-52 justify-end gap-2">
                        <div
                            class="swiper-btn-prev py-2 px-[11px] cursor-pointer rounded-full bg-white/20 hover:bg-white/30  rotate-180">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-2" viewBox="0 0 5 9">
                                <path stroke="currentColor" d="M1 1l3 3.5L1 8" fill="none" fill-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div
                            class="swiper-btn-next py-2 px-[11px] cursor-pointer rounded-full bg-white/20 hover:bg-white/30 ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-2" viewBox="0 0 5 9">
                                <path stroke="currentColor" d="M1 1l3 3.5L1 8" fill="none" fill-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                    <div class="w-full swiper-wrapper">
                        <div class="w-full swiper-slide">
                            <div class="flex flex-wrap items-center justify-between w-full">
                            {{-- 1 --}}
                            <div class="flex flex-col">
                                <div
                                    class=" h-55 rounded-lg overflow-hidden max-h-1/5 flex items-center justify-center max-w-full">
                                    <img src="https://cdn1.epicgames.com/spt-assets/bed013a0fb1e4aa09ce233304d4486c8/dead-as-disco-atazj.png?resize=1&w=360&h=480&quality=medium"
                                        alt="" class="w-full h-full object-cover">
                                </div>
                                <p class="mt-3 text-xs text-gray-400">Base Game</p>
                                <h5 class="mt-2text-sm font-bold">Dead as Disco</h5>
                                <div class="text-white flex items-center gap-2 text-sm">
                                    <span
                                        class="bg-[#26bbff] px-2.5 py-0.50 text-xs text-black rounded-xl font-medium">-20%</span>
                                </div>
                                <span>
                                    <s class="opacity-70">Rp 214.999*</s>
                                    <p class="">Rp 171.999</p>
                                </span>
                            </div>
                            {{-- 2 --}}
                            <div class="flex flex-col">
                                <div
                                    class=" h-55 rounded-lg overflow-hidden max-h-1/5 flex items-center justify-center max-w-full">
                                    <img src="https://cdn1.epicgames.com/spt-assets/51e62a4ff7c341b08ce2e84f18846c06/vampire-crawlers-the-turbo-wildcard-from-vampire-survivors-14xln.jpg?resize=1&w=360&h=480&quality=medium"
                                        alt="" class="w-full h-full object-cover">
                                </div>
                                <p class="mt-2 text-xs text-gray-400">Base Game</p>
                                <h5 class="mt-1 text-sm font-bold">Vampire Crawlers: <br>The Turbo Wildcard</h5>
                                <span>
                                    <p class="mt-5">Rp 90.999</p>
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <div
                                    class=" h-55 rounded-lg overflow-hidden max-h-1/5 flex items-center justify-center max-w-full">
                                    <img src="https://cdn1.epicgames.com/spt-assets/3c0718aa4b08464eb049d8c05afab31b/fortune-seller-jyflw.jpg?resize=1&w=360&h=480&quality=medium"
                                        alt="" class="w-full h-full object-cover">
                                </div>
                                <p class="mt-2 text-xs text-gray-400">Base Game</p>
                                <h5 class="mt-1 text-sm font-bold">Fortune Seller</h5>
                                <span>
                                    <p class="mt-9">Rp 48.999</p>
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <div
                                    class=" h-55 rounded-lg overflow-hidden max-h-1/5 flex items-center justify-center max-w-full">
                                    <img src="https://cdn1.epicgames.com/spt-assets/f4ed183d500541f59de1107643bf9c96/arcadia-fallen-ii-11u2f.jpg?resize=1&w=360&h=480&quality=medium"
                                        alt="" class="w-full h-full object-cover">
                                </div>
                                <p class="mt-2 text-xs text-gray-400">Base Game</p>
                                <h5 class="mt-1 text-sm font-bold">Fortune Seller</h5>
                                <span>
                                    <p class="mt-9">Rp 48.999</p>
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <div
                                    class=" h-55 rounded-lg overflow-hidden max-h-1/5 flex items-center justify-center max-w-full">
                                    <img src="https://cdn1.epicgames.com/spt-assets/9dc4c8c6c00349d79560466a43ad2ca6/screamer-m8rwi.jpg?resize=1&w=360&h=480&quality=medium"
                                        alt="" class="w-full h-full object-cover">
                                </div>
                                <p class="mt-2 text-xs text-gray-400">Base Game</p>
                                <h5 class="mt-1 text-sm font-bold">Fortune Seller</h5>
                                <span>
                                    <p class="mt-9">Rp 699.000</p>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="w-full swiper-slide">
                        <div class="flex flex-wrap items-center justify-between w-full">
                            {{-- 1 --}}
                            <div class="flex flex-col">
                                <div class="flex flex-col">
                                    <div
                                        class=" h-55 rounded-lg overflow-hidden max-h-1/5 flex items-center justify-center max-w-full">
                                        <img src="https://cdn1.epicgames.com/spt-assets/f4ed183d500541f59de1107643bf9c96/arcadia-fallen-ii-11u2f.jpg?resize=1&w=360&h=480&quality=medium"
                                            alt="" class="w-full h-full object-cover">
                                    </div>
                                    <p class="mt-2 text-xs text-gray-400">Base Game</p>
                                    <h5 class="mt-1 text-sm font-bold">Fortune Seller</h5>
                                    <span>
                                        <p class="mt-9">Rp 48.999</p>
                                    </span>
                                </div>
                            </div>
                            {{-- 2 --}}
                            <div class="flex flex-col">
                                <div
                                    class=" h-55 rounded-lg overflow-hidden max-h-1/5 flex items-center justify-center max-w-full">
                                    <img src="https://cdn1.epicgames.com/spt-assets/51e62a4ff7c341b08ce2e84f18846c06/vampire-crawlers-the-turbo-wildcard-from-vampire-survivors-14xln.jpg?resize=1&w=360&h=480&quality=medium"
                                        alt="" class="w-full h-full object-cover">
                                </div>
                                <p class="mt-2 text-xs text-gray-400">Base Game</p>
                                <h5 class="mt-1 text-sm font-bold">Vampire Crawlers: <br>The Turbo Wildcard</h5>
                                <span>
                                    <p class="mt-5">Rp 90.999</p>
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <div
                                    class=" h-55 rounded-lg overflow-hidden max-h-1/5 flex items-center justify-center max-w-full">
                                    <img src="https://cdn1.epicgames.com/spt-assets/3c0718aa4b08464eb049d8c05afab31b/fortune-seller-jyflw.jpg?resize=1&w=360&h=480&quality=medium"
                                        alt="" class="w-full h-full object-cover">
                                </div>
                                <p class="mt-2 text-xs text-gray-400">Base Game</p>
                                <h5 class="mt-1 text-sm font-bold">Fortune Seller</h5>
                                <span>
                                    <p class="mt-9">Rp 48.999</p>
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <div
                                    class=" h-55 rounded-lg overflow-hidden max-h-1/5 flex items-center justify-center max-w-full">
                                    <img src="https://cdn1.epicgames.com/spt-assets/f4ed183d500541f59de1107643bf9c96/arcadia-fallen-ii-11u2f.jpg?resize=1&w=360&h=480&quality=medium"
                                        alt="" class="w-full h-full object-cover">
                                </div>
                                <p class="mt-2 text-xs text-gray-400">Base Game</p>
                                <h5 class="mt-1 text-sm font-bold">Fortune Seller</h5>
                                <span>
                                    <p class="mt-9">Rp 48.999</p>
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <div
                                    class=" h-55 rounded-lg overflow-hidden max-h-1/5 flex items-center justify-center max-w-full">
                                    <img src="https://cdn1.epicgames.com/spt-assets/9dc4c8c6c00349d79560466a43ad2ca6/screamer-m8rwi.jpg?resize=1&w=360&h=480&quality=medium"
                                        alt="" class="w-full h-full object-cover">
                                </div>
                                <p class="mt-2 text-xs text-gray-400">Base Game</p>
                                <h5 class="mt-1 text-sm font-bold">Fortune Seller</h5>
                                <span>
                                    <p class="mt-9">Rp 699.000</p>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="w-full">
                        <div
                            class="h-100 relative mt-15 rounded-lg overflow-hidden max-h-1/6 flex items-center justify-center max-w-full">
                            <img src="https://cdn2.unrealengine.com/uefn-40-00-akitaexemplars-egs-twifbanner-desktop-2912x800-2912x800-13ef7f29bc9f.jpg?resize=1&w=1920&h=1080&quality=medium"
                                alt="" class="h-full object-cover">
                            <div
                                class="absolute w-full h-2/3 bottom-0 left-0 pb-2 flex items-center justify-between flex-col">
                                <img class="w-74 object-cover"
                                    src="https://cdn2.unrealengine.com/uefn-40-00-akitaexemplars-egs-twifbanner-logo-500x500-700x137-45492a55cd5c.png?resize=1&w=480&h=270&quality=medium"
                                    alt="">
                                <div class="flex flex-col items-center justify-between h-1/2">
                                    <div class="text-center">
                                        <h3 class="text-white text-xl font-bold">Minggu Ini di Fortnite</h3>
                                        <p class="text-white/80 text-lg">Temukan game baru dan penawaran eksklusif!</p>
                                    </div>
                                    <button
                                        class="font-semibold text-sm bg-primary text-background px-5.5 py-3 rounded-xl tracking-wide cursor-pointer">
                                        Temukan Sekarang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class=" mt-10 flex flex-col swiper gameSwiper2 w-full items-center justify-between">
                <div class="flex w-full items-center justify-between py-4">
                    <h2 class="text-xl font-bold">Diskon Unggulan</h2>
                    <div class="flex h-full w-52 justify-end gap-2">
                        <div
                            class="swiper2-btn-prev py-2 px-[11px] cursor-pointer rounded-full bg-white/20 hover:bg-white/30  rotate-180">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-2" viewBox="0 0 5 9">
                                <path stroke="currentColor" d="M1 1l3 3.5L1 8" fill="none" fill-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div
                            class="swiper2-btn-next py-2 px-[11px] cursor-pointer rounded-full bg-white/20 hover:bg-white/30 ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-2" viewBox="0 0 5 9">
                                <path stroke="currentColor" d="M1 1l3 3.5L1 8" fill="none" fill-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="w-full swiper-wrapper">
                    <div class="w-full swiper-slide">
                        <div class="flex flex-wrap items-center justify-between w-full">
                            {{-- 1 --}}
                            <div class="flex flex-col">
                                <div
                                    class=" h-55 rounded-lg overflow-hidden max-h-1/5 flex items-center justify-center max-w-full">
                                    <img src="https://cdn1.epicgames.com/spt-assets/bed013a0fb1e4aa09ce233304d4486c8/dead-as-disco-atazj.png?resize=1&w=360&h=480&quality=medium"
                                        alt="" class="w-full h-full object-cover">
                                </div>
                                <p class="mt-3 text-xs text-gray-400">Base Game</p>
                                <h5 class="mt-2text-sm font-bold">Dead as Disco</h5>
                                <div class="text-white flex items-center gap-2 text-sm">
                                    <span
                                        class="bg-[#26bbff] px-2.5 py-0.50 text-xs text-black rounded-xl font-medium">-20%</span>
                                </div>
                                <span>
                                    <s class="opacity-70">Rp 214.999*</s>
                                    <p class="">Rp 171.999</p>
                                </span>
                            </div>
                            {{-- 2 --}}
                            <div class="flex flex-col">
                                <div
                                    class=" h-55 rounded-lg overflow-hidden max-h-1/5 flex items-center justify-center max-w-full">
                                    <img src="https://cdn1.epicgames.com/spt-assets/51e62a4ff7c341b08ce2e84f18846c06/vampire-crawlers-the-turbo-wildcard-from-vampire-survivors-14xln.jpg?resize=1&w=360&h=480&quality=medium"
                                        alt="" class="w-full h-full object-cover">
                                </div>
                                <p class="mt-2 text-xs text-gray-400">Base Game</p>
                                <h5 class="mt-1 text-sm font-bold">Vampire Crawlers: <br>The Turbo Wildcard</h5>
                                <span>
                                    <p class="mt-5">Rp 90.999</p>
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <div
                                    class=" h-55 rounded-lg overflow-hidden max-h-1/5 flex items-center justify-center max-w-full">
                                    <img src="https://cdn1.epicgames.com/spt-assets/3c0718aa4b08464eb049d8c05afab31b/fortune-seller-jyflw.jpg?resize=1&w=360&h=480&quality=medium"
                                        alt="" class="w-full h-full object-cover">
                                </div>
                                <p class="mt-2 text-xs text-gray-400">Base Game</p>
                                <h5 class="mt-1 text-sm font-bold">Fortune Seller</h5>
                                <span>
                                    <p class="mt-9">Rp 48.999</p>
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <div
                                    class=" h-55 rounded-lg overflow-hidden max-h-1/5 flex items-center justify-center max-w-full">
                                    <img src="https://cdn1.epicgames.com/spt-assets/f4ed183d500541f59de1107643bf9c96/arcadia-fallen-ii-11u2f.jpg?resize=1&w=360&h=480&quality=medium"
                                        alt="" class="w-full h-full object-cover">
                                </div>
                                <p class="mt-2 text-xs text-gray-400">Base Game</p>
                                <h5 class="mt-1 text-sm font-bold">Fortune Seller</h5>
                                <span>
                                    <p class="mt-9">Rp 48.999</p>
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <div
                                    class=" h-55 rounded-lg overflow-hidden max-h-1/5 flex items-center justify-center max-w-full">
                                    <img src="https://cdn1.epicgames.com/spt-assets/9dc4c8c6c00349d79560466a43ad2ca6/screamer-m8rwi.jpg?resize=1&w=360&h=480&quality=medium"
                                        alt="" class="w-full h-full object-cover">
                                </div>
                                <p class="mt-2 text-xs text-gray-400">Base Game</p>
                                <h5 class="mt-1 text-sm font-bold">Fortune Seller</h5>
                                <span>
                                    <p class="mt-9">Rp 699.000</p>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="w-full swiper-slide">
                        <div class="flex flex-wrap items-center justify-between w-full">
                            {{-- 1 --}}
                            <div class="flex flex-col">
                                <div class="flex flex-col">
                                    <div
                                        class=" h-55 rounded-lg overflow-hidden max-h-1/5 flex items-center justify-center max-w-full">
                                        <img src="https://cdn1.epicgames.com/spt-assets/f4ed183d500541f59de1107643bf9c96/arcadia-fallen-ii-11u2f.jpg?resize=1&w=360&h=480&quality=medium"
                                            alt="" class="w-full h-full object-cover">
                                    </div>
                                    <p class="mt-2 text-xs text-gray-400">Base Game</p>
                                    <h5 class="mt-1 text-sm font-bold">Fortune Seller</h5>
                                    <span>
                                        <p class="mt-9">Rp 48.999</p>
                                    </span>
                                </div>
                            </div>
                            {{-- 2 --}}
                            <div class="flex flex-col">
                                <div
                                    class=" h-55 rounded-lg overflow-hidden max-h-1/5 flex items-center justify-center max-w-full">
                                    <img src="https://cdn1.epicgames.com/spt-assets/51e62a4ff7c341b08ce2e84f18846c06/vampire-crawlers-the-turbo-wildcard-from-vampire-survivors-14xln.jpg?resize=1&w=360&h=480&quality=medium"
                                        alt="" class="w-full h-full object-cover">
                                </div>
                                <p class="mt-2 text-xs text-gray-400">Base Game</p>
                                <h5 class="mt-1 text-sm font-bold">Vampire Crawlers: <br>The Turbo Wildcard</h5>
                                <span>
                                    <p class="mt-5">Rp 90.999</p>
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <div
                                    class=" h-55 rounded-lg overflow-hidden max-h-1/5 flex items-center justify-center max-w-full">
                                    <img src="https://cdn1.epicgames.com/spt-assets/3c0718aa4b08464eb049d8c05afab31b/fortune-seller-jyflw.jpg?resize=1&w=360&h=480&quality=medium"
                                        alt="" class="w-full h-full object-cover">
                                </div>
                                <p class="mt-2 text-xs text-gray-400">Base Game</p>
                                <h5 class="mt-1 text-sm font-bold">Fortune Seller</h5>
                                <span>
                                    <p class="mt-9">Rp 48.999</p>
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <div
                                    class=" h-55 rounded-lg overflow-hidden max-h-1/5 flex items-center justify-center max-w-full">
                                    <img src="https://cdn1.epicgames.com/spt-assets/f4ed183d500541f59de1107643bf9c96/arcadia-fallen-ii-11u2f.jpg?resize=1&w=360&h=480&quality=medium"
                                        alt="" class="w-full h-full object-cover">
                                </div>
                                <p class="mt-2 text-xs text-gray-400">Base Game</p>
                                <h5 class="mt-1 text-sm font-bold">Fortune Seller</h5>
                                <span>
                                    <p class="mt-9">Rp 48.999</p>
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <div
                                    class=" h-55 rounded-lg overflow-hidden max-h-1/5 flex items-center justify-center max-w-full">
                                    <img src="https://cdn1.epicgames.com/spt-assets/9dc4c8c6c00349d79560466a43ad2ca6/screamer-m8rwi.jpg?resize=1&w=360&h=480&quality=medium"
                                        alt="" class="w-full h-full object-cover">
                                </div>
                                <p class="mt-2 text-xs text-gray-400">Base Game</p>
                                <h5 class="mt-1 text-sm font-bold">Fortune Seller</h5>
                                <span>
                                    <p class="mt-9">Rp 699.000</p>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="px-40 mt-10 h-min max-w-full w-full flex items-center gap-x-5">
        <div class="flex flex-col gap-4 max-w-full w-full">
            <div class="flex w-full items-center justify-between py-4 mt-15"> 
                <div class="flex flex-wrap items-center justify-between w-full">
                    <div class="flex flex-col">
                        <div
                            class=" m-1 h-45 w-75 rounded-lg overflow-hidden max-h-1/3 flex items-center justify-center max-w-full">
                                <img src="https://cdn2.unrealengine.com/id-egs-deals-of-the-week-breaker-1920x1080-6a4ee22de7be.jpg?resize=1&w=854&h=480&quality=medium"
                                alt="" class="w-full h-full object-cover">
                        </div>
                            <h5 class="mt-7 text-xl font-bold">Borderlands®4 Deluxe <br>Edition</h5>
                        <div class="text-white flex items-center gap-2 text-sm mt-5">
                            <span class="bg-[#26bbff] px-2.5 py-0.50 text-xs text-black rounded-xl font-medium">-40%</span>
                            <span>
                                <s class="opacity-70">Rp 1.090.000*</s>
                            </span>
                            <span>
                                <p class="">Rp 654.000</p>
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <div
                            class=" m-1 h-45 w-75 rounded-lg overflow-hidden max-h-1/3 flex items-center justify-center max-w-full">
                                <img src="https://cdn2.unrealengine.com/id-egs-deals-of-the-week-breaker-1920x1080-500df78b62e8.jpg?resize=1&w=854&h=480&quality=medium"
                                alt="" class="w-full h-full object-cover">
                        </div>
                            <h5 class="mt-7 text-xl font-bold">Docked</h5>
                        <div class="text-white flex items-center gap-2 text-sm mt-11">
                            <span class="bg-[#26bbff] px-2.5 py-0.50 text-xs text-black rounded-xl font-medium">-20%</span>
                            <span>
                                <s class="opacity-70">Rp 259.000*</s>
                            </span>
                            <span>
                                <p class="">Rp 207.200</p>
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <div
                            class=" m-1 h-45 w-75 rounded-lg overflow-hidden max-h-1/3 flex items-center justify-center max-w-full">
                                <img src="https://cdn2.unrealengine.com/id-sales-specials-dotw-breaker-asset-1920x1080-e7255cdf8c3c.jpg?resize=1&w=854&h=480&quality=medium"
                                alt="" class="w-full h-full object-cover">
                        </div>
                            <h5 class="mt-3 text-xl font-bold">Lihat semua promo untuk<br>Minggu ini</h5>
                        <div class="text-white flex items-center gap-2 text-sm mt-5">
                            <button
                    class="font-semibold text-sm bg-white/15 hover:bg-white/30 px-3 py-1.5 rounded-md tracking-wide cursor-pointer">Jelajahi</button>
                            
                        </div>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="min-h-screen flex-items-center p-6">
        <div class="w-full max-w-5xl bg-zinc-900 rounded-3xl p-6 m-9 mx-auto">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3>
                <button class="flex items-center cursor-pointer">
                <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" class="fill-primary w-6 h-6">
                    <path
                        d="M30.443 16.605H19.72v-3.46h10.724v3.46zm-2.075 12.308c0 .397-.576.838-1.402.838h-7.247V17.99h8.648v10.924zm-14.876.838h4.843v-18.68h-4.843v18.68zm-10.033-.838V17.99h8.649v11.762H4.861c-.826 0-1.402-.441-1.402-.838zM1.384 13.146h10.724v3.46H1.384v-3.46zm1.773-4.324c0-1.622 1.319-2.94 2.94-2.94 2.752 0 5.093 3.78 5.575 5.88H6.097a2.944 2.944 0 0 1-2.94-2.94zm22.573-2.94c1.621 0 2.94 1.318 2.94 2.94 0 1.621-1.319 2.94-2.94 2.94h-5.574c.481-2.1 2.822-5.88 5.574-5.88zm5.405 5.88h-2.244a4.304 4.304 0 0 0 1.163-2.94 4.329 4.329 0 0 0-4.324-4.325c-2.89 0-5.227 2.813-6.341 5.294a.686.686 0 0 0-.362-.105H12.8a.686.686 0 0 0-.362.105c-1.114-2.481-3.45-5.294-6.34-5.294a4.329 4.329 0 0 0-4.325 4.325c0 1.136.444 2.168 1.163 2.94H.692a.692.692 0 0 0-.692.692v4.843c0 .382.31.692.692.692h1.384v10.924c0 1.246 1.223 2.222 2.785 2.222h22.105c1.562 0 2.785-.976 2.785-2.222V17.99h1.384c.382 0 .692-.31.692-.692v-4.843a.692.692 0 0 0-.692-.692zM15.914 4.151c.381 0 .691-.31.691-.692V.692a.692.692 0 0 0-1.383 0v2.767c0 .382.31.692.692.692">
                    </path>
                </svg>
                </button>
                <h6 class="text-xl font-bold px-3 py-1.5">Game Gratis</h6>
                </div>
                <div class="flex flex-col">
                    <di class="">
                        <button class="border border-zinc-600 text-sm px-5 py-3 rounded-xl hover:bg-zinc-800 transition">Lihat Selengkapnya</button>
                    </di>
                </div>  
            </div>
            <div class="flex flex-wrap items-center justify-between w-full ">
                <div class="flex flex-col">
                    <div
                        class=" m-1 h-70 w-50 rounded-lg overflow-hidden max-h-1/4 flex items-center justify-center max-w-full ">
                            <img src="https://cdn1.epicgames.com/spt-assets/fee7dc25f12c4400a5182c864fd89c66/arranger-a-rolepuzzling-adventure-1dhii.jpg?resize=1&w=360&h=480&quality=medium"
                            alt="" class="w-full h-full object-cover">
                        <div class="bg-sky-500 text-black text-center py-2 text-xs flex justify-center items-center font-semibold h-10">GRATIS SEKARANG</div>
                    </div>
                        <h5 class="mt-4 text-xl font-bold">Arranger: A Role-<br>Puzzling Adventure</h5>
                        <p class="text-zinc-400">Gratis Sekarang - <br>14 Mei pukul 22.00</p>
                </div>
            </div>  
        </div>
    </div>
</body>

</html>