@php
    $cartItems = collect(session('cart', []))->unique()->values();
    $cartCount = $cartItems->count();

    $wishlistCount = auth()->check()
        ? \App\Models\Wishlist::where('user_id', auth()->id())->count()
        : 0;

    $navLinks = [
        ['label' => 'Discover', 'route' => 'home', 'active' => ['home', 'store', 'store.*', 'game.*']],
        ['label' => 'Browse', 'route' => 'jelajahi', 'active' => ['jelajahi']],
        ['label' => 'News', 'route' => 'news.index', 'active' => ['news.*']],
    ];

    $isActive = fn (array $patterns) => request()->routeIs(...$patterns);
@endphp

<header class="relative z-50 w-full border-b border-white/5 bg-[#101014] text-white">
        <div class="flex min-h-18 w-full items-center justify-between gap-5 px-5 sm:px-7">
            <div class="flex min-w-0 items-center gap-8">
                <a href="{{ route('home') }}" class="group flex items-center gap-3">
                    <img src="/img/logo_ph.png" alt="Epic Games" class="h-8 w-auto shrink-0">
                    <svg class="h-3 w-3 shrink-0 text-gray-400 transition-transform duration-200 group-hover:rotate-180 group-hover:text-white" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M18.47 8.97a.75.75 0 1 1 1.06 1.06L12 17.56l-7.53-7.53a.75.75 0 1 1 1.06-1.06L12 15.44z"></path>
                    </svg>
                </a>

                <a href="{{ route('home') }}" class="text-lg font-black tracking-tight text-white">STORE</a>

                <nav class="hidden items-center gap-7 md:flex">
                    <a href="#" class="text-sm font-semibold text-gray-300 transition-colors duration-200 hover:text-white">Support</a>
                    <a href="#" class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-300 transition-colors duration-200 hover:text-white">
                        Distribute
                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M18.47 8.97a.75.75 0 1 1 1.06 1.06L12 17.56l-7.53-7.53a.75.75 0 1 1 1.06-1.06L12 15.44z"></path>
                        </svg>
                    </a>
                </nav>
            </div>

            <div class="hidden items-center gap-4 md:flex">
                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full text-gray-300 transition-colors duration-200 hover:bg-white/10 hover:text-white" aria-label="Language">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0 0c2.2-2.35 3.4-5.38 3.4-9S14.2 5.35 12 3m0 18c-2.2-2.35-3.4-5.38-3.4-9S9.8 5.35 12 3M3.6 9h16.8M3.6 15h16.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </button>

                @auth
                    <a href="{{ route('library.index') }}" class="inline-flex items-center gap-3 rounded-full text-sm font-bold text-gray-200 transition-colors duration-200 hover:text-white">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/15 text-sm text-white">
                            {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                        </span>
                        <span class="hidden max-w-32 truncate lg:inline">{{ auth()->user()->username }}</span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="rounded-md bg-[#26bbff] px-4 py-2 text-sm font-bold text-black transition-colors duration-200 hover:bg-[#56cbff]">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-300 transition-colors duration-200 hover:text-white">Login</a>
                    <a href="{{ route('register') }}" class="rounded-md bg-[#26bbff] px-4 py-2 text-sm font-bold text-black transition-colors duration-200 hover:bg-[#56cbff]">Daftar</a>
                @endauth
            </div>
        </div>
</header>

<nav x-data="{ open: false }" class="sticky top-0 z-50 w-full border-b border-white/5 bg-[#101014]/95 text-white backdrop-blur-xl">
        <div class="mx-auto flex min-h-20 w-full max-w-[1140px] items-center gap-5 px-5 sm:px-8">
            <form action="{{ route('jelajahi') }}" method="GET" class="hidden w-56 shrink-0 transition-all duration-200 ease-out focus-within:w-64 sm:block">
                <label for="store-search" class="sr-only">Search store</label>
                <div class="flex h-11 w-full items-center gap-3 rounded-full bg-[#202027] px-4 text-sm text-gray-300 ring-1 ring-transparent transition-colors duration-200 ease-out focus-within:bg-[#25252d] focus-within:ring-white/10 hover:bg-[#25252d]">
                    <svg class="h-4 w-4 shrink-0 text-gray-400" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="m21 21-4.35-4.35m1.35-5.15a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <input
                        id="store-search"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Search store"
                        class="min-w-0 flex-1 bg-transparent text-sm text-gray-100 placeholder:text-gray-400 focus:outline-none"
                    >
                </div>
            </form>

            <div class="flex items-center gap-1 md:hidden">
                <a href="{{ route('home') }}" class="text-sm font-bold tracking-wide text-white">STORE</a>
            </div>

            <div class="hidden flex-1 items-center gap-7 md:flex">
                @foreach ($navLinks as $link)
                    <a
                        href="{{ route($link['route']) }}"
                        class="text-sm font-semibold transition-colors duration-200 ease-out hover:text-white {{ $isActive($link['active']) ? 'text-white' : 'text-gray-400' }}"
                    >
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>

            <div class="ml-auto hidden items-center gap-5 md:flex">
                <a
                    href="{{ route('wishlist.index') }}"
                    class="group inline-flex items-center gap-2 text-sm font-semibold transition-colors duration-200 ease-out hover:text-white {{ request()->routeIs('wishlist.*') ? 'text-white' : 'text-gray-400' }}"
                >
                    <span>Wishlist</span>
                    @if ($wishlistCount > 0)
                        <span class="inline-flex min-w-6 items-center justify-center rounded-full bg-[#26bbff] px-2 py-0.5 text-xs font-bold leading-none text-black transition-transform duration-200 ease-out group-hover:scale-105">
                            {{ $wishlistCount > 99 ? '99+' : $wishlistCount }}
                        </span>
                    @endif
                </a>

                <a
                    href="{{ route('cart.index') }}"
                    class="group inline-flex items-center gap-2 text-sm font-semibold transition-colors duration-200 ease-out hover:text-white {{ request()->routeIs('cart.*') ? 'text-white' : 'text-gray-400' }}"
                >
                    <span>Cart</span>
                    @if ($cartCount > 0)
                        <span class="inline-flex min-w-6 items-center justify-center rounded-full bg-[#26bbff] px-2 py-0.5 text-xs font-bold leading-none text-black transition-transform duration-200 ease-out group-hover:scale-105">
                            {{ $cartCount > 99 ? '99+' : $cartCount }}
                        </span>
                    @endif
                </a>
            </div>

            <button
                type="button"
                class="ml-auto inline-flex h-10 w-10 items-center justify-center rounded-full bg-[#202027] text-gray-300 transition-colors duration-200 hover:bg-[#2a2a33] hover:text-white md:hidden"
                aria-label="Toggle navigation"
                @click="open = !open"
            >
                <svg x-show="!open" class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <svg x-show="open" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="m6 6 12 12M18 6 6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <div
            x-show="open"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="border-t border-white/5 bg-[#101014] px-5 py-5 md:hidden"
        >
            <form action="{{ route('jelajahi') }}" method="GET" class="mb-5">
                <label for="store-search-mobile" class="sr-only">Search store</label>
                <div class="flex h-11 items-center gap-3 rounded-full bg-[#202027] px-4 text-sm text-gray-300 ring-1 ring-transparent transition-colors duration-200 focus-within:ring-white/10">
                    <svg class="h-4 w-4 shrink-0 text-gray-400" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="m21 21-4.35-4.35m1.35-5.15a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <input
                        id="store-search-mobile"
                        name="q"
                        value="{{ request('q') }}"
                        placeholder="Search store"
                        class="min-w-0 flex-1 bg-transparent text-sm text-gray-100 placeholder:text-gray-400 focus:outline-none"
                    >
                </div>
            </form>

            <div class="grid gap-1 text-sm font-semibold">
                @foreach ($navLinks as $link)
                    <a href="{{ route($link['route']) }}" class="rounded-lg px-3 py-2 transition-colors duration-200 {{ $isActive($link['active']) ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
                <a href="{{ route('wishlist.index') }}" class="flex items-center justify-between rounded-lg px-3 py-2 transition-colors duration-200 {{ request()->routeIs('wishlist.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                    <span>Wishlist</span>
                    @if ($wishlistCount > 0)
                        <span class="rounded-full bg-[#26bbff] px-2 py-0.5 text-xs font-bold text-black">{{ $wishlistCount > 99 ? '99+' : $wishlistCount }}</span>
                    @endif
                </a>
                <a href="{{ route('cart.index') }}" class="flex items-center justify-between rounded-lg px-3 py-2 transition-colors duration-200 {{ request()->routeIs('cart.*') ? 'bg-white/10 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">
                    <span>Cart</span>
                    @if ($cartCount > 0)
                        <span class="rounded-full bg-[#26bbff] px-2 py-0.5 text-xs font-bold text-black">{{ $cartCount > 99 ? '99+' : $cartCount }}</span>
                    @endif
                </a>
            </div>

            <div class="mt-4 border-t border-white/5 pt-4 text-sm font-semibold">
                @auth
                    <div class="grid gap-1">
                        <a href="{{ route('library.index') }}" class="rounded-lg px-3 py-2 text-gray-400 transition-colors duration-200 hover:bg-white/5 hover:text-white">Library</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full rounded-lg px-3 py-2 text-left text-gray-400 transition-colors duration-200 hover:bg-white/5 hover:text-white">
                                Logout
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="rounded-lg px-3 py-2 text-gray-400 transition-colors duration-200 hover:bg-white/5 hover:text-white">Login</a>
                        <a href="{{ route('register') }}" class="rounded-md bg-[#26bbff] px-3 py-2 font-bold text-black transition-colors duration-200 hover:bg-[#56cbff]">Daftar</a>
                    </div>
                @endauth
            </div>
        </div>
</nav>