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
            <button class="f text-gray-400 hover:text-white">Temukan</button>
            <button class="ont-semibold border-b-2 border-white pb-1">Jelajahi</button>
            <button class="text-gray-400 hover:text-white">Berita</button>
        </div>
    </div>
<div class="px-40 mt-10 h-min max-w-full w-full flex items-center gap-x-5">
    <div class="flex flex-col gap-4 max-w-full w-full">

        <div class="swiper gameSwiper w-full overflow-hidden">

            <!-- Header -->
            <div class="flex w-full items-center justify-between py-4">
                <h1 class="text-4xl font-extrabold text-primary w-full text-left">
                    Genre Populer
                </h1>

                <div class="flex h-full w-52 justify-end gap-2">

                    <div class="swiper-btn-prev py-2 px-[11px] cursor-pointer rounded-full bg-white/20 hover:bg-white/30 rotate-180">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-2" viewBox="0 0 5 9">
                            <path stroke="currentColor" d="M1 1l3 3.5L1 8" fill="none"></path>
                        </svg>
                    </div>

                    <div class="swiper-btn-next py-2 px-[11px] cursor-pointer rounded-full bg-white/20 hover:bg-white/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-2" viewBox="0 0 5 9">
                            <path stroke="currentColor" d="M1 1l3 3.5L1 8" fill="none"></path>
                        </svg>
                    </div>

                </div>
            </div>


            <!-- SWIPER WRAPPER -->
            <div class="w-full swiper-wrapper">

                <!-- SLIDE 1 -->
                <div class="w-full swiper-slide">
                    <div class="flex flex-wrap items-center justify-between w-full">
                        <div class="grid grid-cols-4 gap-3.75 max-h-1/4 w-full">

                            <div class="bg-[#181A22] rounded-xl p-4 w-55">
                                <div class="relative h-40">
                                    <img src="https://cdn1.epicgames.com/spt-assets/282a605f413744ac840762f1ecfe5c94/duriano-wxfky.jpg"
                                        class="absolute left-0 top-0 w-27 h-35 rounded-lg object-cover z-10">

                                    <img src="https://cdn1.epicgames.com/spt-assets/86af6069e1b34c0b9da4f07ba4541243/sunset-motel-1unhs.png"
                                        class="absolute left-10 top-0 w-27 h-35 rounded-lg object-cover z-50">

                                    <img src="https://cdn1.epicgames.com/spt-assets/3e16f93ac6da48fc85cd073a20ab4e6e/the-artful-escape-yo3ia.jpg"
                                        class="absolute left-20 top-0 w-27 h-35 rounded-lg object-cover z-30">
                                </div>
                                <h2 class="text-white text-base text-center mt-1">
                                    City Building Games
                                </h2>
                            </div>

                            <div class="bg-[#181A22] rounded-xl p-4 w-55">
                                <div class="relative h-40">
                                    <img src="https://cdn1.epicgames.com/spt-assets/41308e2aa444449caa2a5ec4c5a8ff78/sqwrk-115w1.png"
                                        class="absolute left-0 top-0 w-27 h-35 rounded-lg object-cover z-10">

                                    <img src="https://cdn1.epicgames.com/spt-assets/f3c1b731e7a845ee840bb8e318216183/hank-drowning-on-dry-land-16d4k.png"
                                        class="absolute left-10 top-0 w-27 h-35 rounded-lg object-cover z-50">

                                    <img src="https://cdn1.epicgames.com/spt-assets/ee49b95027e44abe868f3aaf95ef0522/open-robot-m7nvx.jpg"
                                        class="absolute left-20 top-0 w-27 h-35 rounded-lg object-cover z-30">
                                </div>
                                <h2 class="text-white text-base text-center mt-1">
                                    Co-Op Games
                                </h2>
                            </div>

                            <div class="bg-[#181A22] rounded-xl p-4 w-55">
                                <div class="relative h-40">
                                    <img src="https://cdn1.epicgames.com/spt-assets/41308e2aa444449caa2a5ec4c5a8ff78/sqwrk-115w1.png"
                                        class="absolute left-0 top-0 w-27 h-35 rounded-lg object-cover z-10">

                                    <img src="https://cdn1.epicgames.com/spt-assets/f3c1b731e7a845ee840bb8e318216183/hank-drowning-on-dry-land-16d4k.png"
                                        class="absolute left-10 top-0 w-27 h-35 rounded-lg object-cover z-50">

                                    <img src="https://cdn1.epicgames.com/spt-assets/ee49b95027e44abe868f3aaf95ef0522/open-robot-m7nvx.jpg"
                                        class="absolute left-20 top-0 w-27 h-35 rounded-lg object-cover z-30">
                                </div>
                                <h2 class="text-white text-base text-center mt-1">
                                    Cross Platform Games
                                </h2>
                            </div>

                            <div class="bg-[#181A22] rounded-xl p-4 w-55">
                                <div class="relative h-40">
                                    <img src="https://cdn1.epicgames.com/spt-assets/41308e2aa444449caa2a5ec4c5a8ff78/sqwrk-115w1.png"
                                        class="absolute left-0 top-0 w-27 h-35 rounded-lg object-cover z-10">

                                    <img src="https://cdn1.epicgames.com/spt-assets/f3c1b731e7a845ee840bb8e318216183/hank-drowning-on-dry-land-16d4k.png"
                                        class="absolute left-10 top-0 w-27 h-35 rounded-lg object-cover z-50">

                                    <img src="https://cdn1.epicgames.com/spt-assets/ee49b95027e44abe868f3aaf95ef0522/open-robot-m7nvx.jpg"
                                        class="absolute left-20 top-0 w-27 h-35 rounded-lg object-cover z-30">
                                </div>
                                <h2 class="text-white text-base text-center mt-1">
                                    Fantasy Games
                                </h2>
                            </div>

                        </div>
                    </div>
                </div>


                <!-- SLIDE 2 -->
                <div class="w-full swiper-slide">
                    <div class="flex flex-wrap items-center justify-between w-full">
                        <div class="grid grid-cols-4 gap-3.75 max-h-1/4 w-full">

                            <div class="bg-[#181A22] rounded-xl p-4 w-55">
                                <div class="relative h-40">
                                    <img src="https://cdn1.epicgames.com/spt-assets/41308e2aa444449caa2a5ec4c5a8ff78/sqwrk-115w1.png"
                                        class="absolute left-0 top-0 w-27 h-35 rounded-lg object-cover z-10">

                                    <img src="https://cdn1.epicgames.com/spt-assets/f3c1b731e7a845ee840bb8e318216183/hank-drowning-on-dry-land-16d4k.png"
                                        class="absolute left-10 top-0 w-27 h-35 rounded-lg object-cover z-50">

                                    <img src="https://cdn1.epicgames.com/spt-assets/ee49b95027e44abe868f3aaf95ef0522/open-robot-m7nvx.jpg"
                                        class="absolute left-20 top-0 w-27 h-35 rounded-lg object-cover z-30">
                                </div>
                                <h2 class="text-white text-base text-center mt-1">
                                    RPG Games
                                </h2>
                            </div>

                            <div class="bg-[#181A22] rounded-xl p-4 w-55">
                                <div class="relative h-40">
                                    <img src="https://cdn1.epicgames.com/spt-assets/41308e2aa444449caa2a5ec4c5a8ff78/sqwrk-115w1.png"
                                        class="absolute left-0 top-0 w-27 h-35 rounded-lg object-cover z-10">

                                    <img src="https://cdn1.epicgames.com/spt-assets/f3c1b731e7a845ee840bb8e318216183/hank-drowning-on-dry-land-16d4k.png"
                                        class="absolute left-10 top-0 w-27 h-35 rounded-lg object-cover z-50">

                                    <img src="https://cdn1.epicgames.com/spt-assets/ee49b95027e44abe868f3aaf95ef0522/open-robot-m7nvx.jpg"
                                        class="absolute left-20 top-0 w-27 h-35 rounded-lg object-cover z-30">
                                </div>
                                <h2 class="text-white text-base text-center mt-1">
                                    Horror Games
                                </h2>
                            </div>

                            <div class="bg-[#181A22] rounded-xl p-4 w-55">
                                <div class="relative h-40">
                                    <img src="https://cdn1.epicgames.com/spt-assets/41308e2aa444449caa2a5ec4c5a8ff78/sqwrk-115w1.png"
                                        class="absolute left-0 top-0 w-27 h-35 rounded-lg object-cover z-10">

                                    <img src="https://cdn1.epicgames.com/spt-assets/f3c1b731e7a845ee840bb8e318216183/hank-drowning-on-dry-land-16d4k.png"
                                        class="absolute left-10 top-0 w-27 h-35 rounded-lg object-cover z-50">

                                    <img src="https://cdn1.epicgames.com/spt-assets/ee49b95027e44abe868f3aaf95ef0522/open-robot-m7nvx.jpg"
                                        class="absolute left-20 top-0 w-27 h-35 rounded-lg object-cover z-30">
                                </div>
                                <h2 class="text-white text-base text-center mt-1">
                                    Racing Games
                                </h2>
                            </div>

                            <div class="bg-[#181A22] rounded-xl p-4 w-55">
                                <div class="relative h-40">
                                    <img src="https://cdn1.epicgames.com/spt-assets/41308e2aa444449caa2a5ec4c5a8ff78/sqwrk-115w1.png"
                                        class="absolute left-0 top-0 w-27 h-35 rounded-lg object-cover z-10">

                                    <img src="https://cdn1.epicgames.com/spt-assets/f3c1b731e7a845ee840bb8e318216183/hank-drowning-on-dry-land-16d4k.png"
                                        class="absolute left-10 top-0 w-27 h-35 rounded-lg object-cover z-50">

                                    <img src="https://cdn1.epicgames.com/spt-assets/ee49b95027e44abe868f3aaf95ef0522/open-robot-m7nvx.jpg"
                                        class="absolute left-20 top-0 w-27 h-35 rounded-lg object-cover z-30">
                                </div>
                                <h2 class="text-white text-base text-center mt-1">
                                    Sports Games
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>
<div class=" text-white min-h-screen px-4 max-w-full w-full flex items-center justify-center">
    <div class="px-35 mt-10 h-min max-w-full w-full flex items-center gap-x-3">
    <div class="flex flex-col gap-4 max-w-full w-full">
        <div class="flex items-center gap-4 mb-6 text-sm">
            <span class="text-zinc-400 text-sm">Tampilkan:</span>
                <button class="flex items-center gap-2 text-sm">
                    <span">Rilisan Terbaru</span>
                    <button class="flex items-left cursor-pointer">
                        <svg viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg" class="fill-primary w-6 h-6">
                        <path
                            d="M18.707 9.707L12 16.414 5.293 9.707l1.414-1.414L12 13.586l5.293-5.293 1.414 1.414z">
                        </path>
                        </svg>
                    </button>
                </button>
                <button class="bg-zinc-700 px-4 py-1 rounded-full flex items-center gap-2">
                    <span>Game</span>
                    <svg viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg" class="fill-primary w-2 h-2">
                        <path
                            d="M14 1.41L12.59 0 7 5.59 1.41 0 0 1.41 5.59 7 0 12.59 1.41 14 7 8.41 12.59 14 14 12.59 8.41 7z">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-4 gap-4">
                <div>
                    <img src="https://cdn1.epicgames.com/spt-assets/71b2aae8c21c425d94ee72139ff47c78/sea-walker-saga-1xbc3.jpg?resize=1&w=360&h=480&quality=medium" class="rounded-lg w-full h-50 object-cover">
                    <p class="text-[11px] text-zinc-400 mt-3">Base Game</p>
                    <h3 class="font-semibold mt-1 text-sm">Sea Walker Saga</h3>
                    <p class="text-sm mt-2 font-medium">Gratis</p>
                </div>
                <div>
                    <img src="https://picsum.photos/300/420?2" class="rounded-lg w-full h-50 object-cover">
                    <p class="text-[11px] text-zinc-400 mt-3">Base Game</p>
                    <h3 class="font-semibold mt-1 text-sm">Dicaelot</h3>
                    <button class="bg-zinc-800 text-[11px] px-2 py-1 rounded mt-2">Kini di Epic</button>
                    <p class="text-sm mt-2 font-medium">Rp 103.999</p>
                </div>
                <div>
                    <img src="https://picsum.photos/300/420?5"
                        class="rounded-lg w-full h-50 object-cover">
                    <p class="text-[11px] text-zinc-400 mt-3">Base Game</p>
                    <h3 class="font-semibold mt-1 text-sm">Deadhaus Sonata</h3>
                    <p class="text-sm mt-2 font-medium">Rp 137.999</p>
                </div>
                <div>
                    <img src="https://picsum.photos/300/420?6" class="rounded-lg w-full h-50 object-cover">
                    <p class="text-[11px] text-zinc-400 mt-3">Base Game</p>
                    <h3 class="font-semibold mt-1 text-sm">Game Of Thrones Kingsroad</h3>
                    <p class="text-sm mt-2 font-medium">Gratis</p>
                </div>
                <div>
                    <img src="https://picsum.photos/300/420?7" class="rounded-lg w-full h-50 object-cover">
                    <p class="text-[11px] text-zinc-400 mt-3">Base Game</p>
                    <h3 class="font-semibold mt-1 text-sm">Night Games</h3>
                    <p class="text-sm mt-2 font-medium">Gratis</p>
                </div>
                <div>
                    <img src="https://picsum.photos/300/420?8" class="rounded-lg w-full h-50 object-cover">
                    <p class="text-[11px] text-zinc-400 mt-3">Base Game</p>
                    <h3 class="font-semibold mt-1 text-sm">Bryo</h3>
                    <p class="text-sm mt-2 font-medium">Gratis</p>
                </div>
                <div>
                    <img src="https://picsum.photos/300/420?9" class="rounded-lg w-full h-50 object-cover">
                    <p class="text-[11px] text-zinc-400 mt-3">Base Game</p>
                    <h3 class="font-semibold mt-1 text-sm">Road to Virtue</h3>
                    <p class="text-sm mt-2 font-medium">Gratis</p>
                </div>


                <div>
                    <img src="https://picsum.photos/300/420?10" class="rounded-lg w-full h-50  object-cover">
                    <p class="text-[11px] text-zinc-400 mt-3">Base Game</p>
                    <h3 class="font-semibold mt-1 text-sm">Flora Frontier</h3>
                    <p class="text-sm mt-2 font-medium">Gratis</p>
                </div>
                                <div>
                    <img src="https://picsum.photos/300/420?1" class="rounded-lg w-full h-50 object-cover">
                    <p class="text-[11px] text-zinc-400 mt-3">Base Game</p>
                    <h3 class="font-semibold mt-1 text-sm">Gravity [NULL]</h3>
                    <p class="text-sm mt-2 font-medium">Gratis</p>
                </div>
                <div>
                    <img src="https://picsum.photos/300/420?2" class="rounded-lg w-full h-50 object-cover">
                    <p class="text-[11px] text-zinc-400 mt-3">Base Game</p>
                    <h3 class="font-semibold mt-1 text-sm">Dicaelot</h3>
                    <button class="bg-zinc-800 text-[11px] px-2 py-1 rounded mt-2">Kini di Epic</button>
                    <p class="text-sm mt-2 font-medium">Rp 103.999</p>
                </div>
                <div>
                    <img src="https://picsum.photos/300/420?5"
                        class="rounded-lg w-full h-50 object-cover">
                    <p class="text-[11px] text-zinc-400 mt-3">Base Game</p>
                    <h3 class="font-semibold mt-1 text-sm">Deadhaus Sonata</h3>
                    <p class="text-sm mt-2 font-medium">Rp 137.999</p>
                </div>
                <div>
                    <img src="https://picsum.photos/300/420?6" class="rounded-lg w-full h-50 object-cover">
                    <p class="text-[11px] text-zinc-400 mt-3">Base Game</p>
                    <h3 class="font-semibold mt-1 text-sm">Game Of Thrones Kingsroad</h3>
                    <p class="text-sm mt-2 font-medium">Gratis</p>
                </div>
                                <div>
                    <img src="https://picsum.photos/300/420?1" class="rounded-lg w-full h-50 object-cover">
                    <p class="text-[11px] text-zinc-400 mt-3">Base Game</p>
                    <h3 class="font-semibold mt-1 text-sm">Gravity [NULL]</h3>
                    <p class="text-sm mt-2 font-medium">Gratis</p>
                </div>
                <div>
                    <img src="https://picsum.photos/300/420?2" class="rounded-lg w-full h-50 object-cover">
                    <p class="text-[11px] text-zinc-400 mt-3">Base Game</p>
                    <h3 class="font-semibold mt-1 text-sm">Dicaelot</h3>
                    <button class="bg-zinc-800 text-[11px] px-2 py-1 rounded mt-2">Kini di Epic</button>
                    <p class="text-sm mt-2 font-medium">Rp 103.999</p>
                </div>
                <div>
                    <img src="https://picsum.photos/300/420?5"
                        class="rounded-lg w-full h-50 object-cover">
                    <p class="text-[11px] text-zinc-400 mt-3">Base Game</p>
                    <h3 class="font-semibold mt-1 text-sm">Deadhaus Sonata</h3>
                    <p class="text-sm mt-2 font-medium">Rp 137.999</p>
                </div>
                <div>
                    <img src="https://picsum.photos/300/420?6" class="rounded-lg w-full h-50 object-cover">
                    <p class="text-[11px] text-zinc-400 mt-3">Base Game</p>
                    <h3 class="font-semibold mt-1 text-sm">Game Of Thrones Kingsroad</h3>
                    <p class="text-sm mt-2 font-medium">Gratis</p>
                </div>
                                <div>
                    <img src="https://picsum.photos/300/420?1" class="rounded-lg w-full h-50 object-cover">
                    <p class="text-[11px] text-zinc-400 mt-3">Base Game</p>
                    <h3 class="font-semibold mt-1 text-sm">Gravity [NULL]</h3>
                    <p class="text-sm mt-2 font-medium">Gratis</p>
                </div>
                <div>
                    <img src="https://picsum.photos/300/420?2" class="rounded-lg w-full h-50 object-cover">
                    <p class="text-[11px] text-zinc-400 mt-3">Base Game</p>
                    <h3 class="font-semibold mt-1 text-sm">Dicaelot</h3>
                    <button class="bg-zinc-800 text-[11px] px-2 py-1 rounded mt-2">Kini di Epic</button>
                    <p class="text-sm mt-2 font-medium">Rp 103.999</p>
                </div>
                <div>
                    <img src="https://picsum.photos/300/420?5"
                        class="rounded-lg w-full h-50 object-cover">
                    <p class="text-[11px] text-zinc-400 mt-3">Base Game</p>
                    <h3 class="font-semibold mt-1 text-sm">Deadhaus Sonata</h3>
                    <p class="text-sm mt-2 font-medium">Rp 137.999</p>
                </div>
                <div>
                    <img src="https://picsum.photos/300/420?6" class="rounded-lg w-full h-50 object-cover">
                    <p class="text-[11px] text-zinc-400 mt-3">Base Game</p>
                    <h3 class="font-semibold mt-1 text-sm">Game Of Thrones Kingsroad</h3>
                    <p class="text-sm mt-2 font-medium">Gratis</p>
                </div>
            </div>
        </div>
        <!-- RIGHT SIDEBAR -->
<!-- RIGHT SIDEBAR -->
<div class="w-75 bg-[#12131A] rounded-xl p-6 h-fit self-start text-white"
    x-data="{
        eventOpen: false,
        hargaOpen: false,
        genreOpen: false,
        fiturOpen: false,
        tipeOpen: true,
        platformOpen: false,
        langgananOpen: false
    }">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-5">
        <h2 class="font-semibold">Filter (1)</h2>

        <button class="text-blue-400 text-sm">
            Reset
        </button>
    </div>

    <!-- SEARCH -->
    <input type="text"
        placeholder="Kata Kunci"
        class="w-full bg-[#24242c] rounded-xl px-4 py-2 text-xs mb-5 outline-none">

    <div class="space-y-4 text-sm">

        <!-- EVENT -->
        <div class="border-b border-zinc-800 pb-4">

            <button
                @click="eventOpen = !eventOpen"
                class="flex justify-between items-center w-full">

                <span>Event</span>

                <svg
                    class="w-4 h-4 transition-transform duration-300"
                    :class="eventOpen ? 'rotate-180' : ''"
                    viewBox="0 0 30 30"
                    fill="currentColor">

                    <path d="M18.707 9.707L12 16.414 5.293 9.707l1.414-1.414L12 13.586l5.293-5.293 1.414 1.414z"/>
                </svg>
            </button>

            <div x-show="eventOpen"
                x-transition
                class="mt-4 space-y-3 text-zinc-300">

                <label class="flex gap-3">
                    <input type="checkbox">
                    <span>Game Klasik EA di Epic</span>
                </label>

                <label class="flex gap-3">
                    <input type="checkbox">
                    <span>Peluncuran Pertama</span>
                </label>

                <label class="flex gap-3">
                    <input type="checkbox">
                    <span>Promo Libur Panjang</span>
                </label>

            </div>
        </div>

        <!-- HARGA -->
        <div class="border-b border-zinc-800 pb-4">

            <button
                @click="hargaOpen = !hargaOpen"
                class="flex justify-between items-center w-full">

                <span>Harga</span>

                <svg
                    class="w-4 h-4 transition-transform duration-300"
                    :class="hargaOpen ? 'rotate-180' : ''"
                    viewBox="0 0 30 30"
                    fill="currentColor">

                    <path d="M18.707 9.707L12 16.414 5.293 9.707l1.414-1.414L12 13.586l5.293-5.293 1.414 1.414z"/>
                </svg>
            </button>

            <div x-show="hargaOpen"
                x-transition
                class="mt-4 space-y-3 text-zinc-300">

                <label class="flex gap-3">
                    <input type="checkbox">
                    <span>Gratis</span>
                </label>

                <label class="flex gap-3">
                    <input type="checkbox">
                    <span>Di bawah Rp 140.000</span>
                </label>

                <label class="flex gap-3">
                    <input type="checkbox">
                    <span>Diskon</span>
                </label>

            </div>
        </div>

        <!-- TIPE -->
        <div class="border-b border-zinc-800 pb-4">

            <button
                @click="tipeOpen = !tipeOpen"
                class="flex justify-between items-center w-full">

                <span>Tipe</span>

                <svg
                    class="w-4 h-4 transition-transform duration-300"
                    :class="tipeOpen ? 'rotate-180' : ''"
                    viewBox="0 0 30 30"
                    fill="currentColor">

                    <path d="M18.707 9.707L12 16.414 5.293 9.707l1.414-1.414L12 13.586l5.293-5.293 1.414 1.414z"/>
                </svg>
            </button>

            <div x-show="tipeOpen"
                x-transition
                class="mt-4 space-y-3 text-zinc-300">

                <label class="flex gap-3">
                    <input type="checkbox">
                    <span>Add-On Game</span>
                </label>

                <label class="flex gap-3">
                    <input type="checkbox">
                    <span>Aplikasi</span>
                </label>

                <label class="flex gap-3">
                    <input type="checkbox">
                    <span>Bundel Game</span>
                </label>

                <label class="flex gap-3">
                    <input type="checkbox">
                    <span>Demo Game</span>
                </label>

                <label class="flex gap-3">
                    <input type="checkbox">
                    <span>Edisi Game</span>
                </label>

                <label class="flex gap-3">
                    <input type="checkbox">
                    <span>Game</span>
                </label>

            </div>
        </div>

    </div>
</div>
    </div>
</div>

<script>
new Swiper(".gameSwiper", {
    modules: [Navigation],
    slidesPerView: 1,
    spaceBetween: 20,
    navigation: {
        nextEl: ".swiper-btn-next",
        prevEl: ".swiper-btn-prev",
    }
});
</script>

</body>

</html>