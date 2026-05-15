<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Epic Games Store')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body class="bg-gray-950 text-white min-h-screen flex flex-col antialiased">

    {{-- ===== NAVBAR ===== --}}
    <nav class="bg-gray-900 border-b border-gray-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-lg tracking-tight hover:text-blue-400 transition-colors">
                    <svg class="w-7 h-7 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                    Epic Games
                </a>

                {{-- Nav links --}}
                <div class="hidden md:flex items-center gap-6 text-sm text-gray-300">
                    <a href="{{ route('store') }}"      class="hover:text-white transition-colors {{ request()->routeIs('store') ? 'text-white font-semibold' : '' }}">Store</a>
                    <a href="{{ route('news.index') }}" class="hover:text-white transition-colors {{ request()->routeIs('news.*') ? 'text-white font-semibold' : '' }}">News</a>
                    @auth
                    <a href="{{ route('library.index') }}"  class="hover:text-white transition-colors">Library</a>
                    <a href="{{ route('wishlist.index') }}" class="hover:text-white transition-colors">Wishlist</a>
                    @endauth
                </div>

                {{-- Right side --}}
                <div class="flex items-center gap-3">
                    {{-- Search --}}
                    <form action="{{ route('store.search') }}" method="GET" class="hidden sm:flex">
                        <input name="q" value="{{ request('q') }}" placeholder="Cari game..."
                               class="bg-gray-800 border border-gray-700 text-sm rounded-lg px-3 py-1.5 w-40 focus:outline-none focus:border-blue-500 focus:w-52 transition-all placeholder-gray-500">
                    </form>

                    {{-- Cart icon --}}
                    @auth
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        @php $cartCount = count(session('cart', [])) @endphp
                        @if($cartCount > 0)
                        <span class="absolute -top-0.5 -right-0.5 bg-blue-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">{{ $cartCount }}</span>
                        @endif
                    </a>

                    {{-- User dropdown --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 text-sm text-gray-300 hover:text-white transition-colors">
                            <div class="w-7 h-7 bg-blue-600 rounded-full flex items-center justify-center text-xs font-bold">
                                {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                            </div>
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-transition
                             class="absolute right-0 mt-2 w-44 bg-gray-800 border border-gray-700 rounded-xl shadow-lg py-1 text-sm">
                            <div class="px-3 py-2 border-b border-gray-700 text-gray-400 text-xs">{{ auth()->user()->username }}</div>
                            @if(auth()->user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 hover:bg-gray-700 transition-colors">Admin Panel</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-3 py-2 hover:bg-gray-700 text-red-400 transition-colors">Logout</button>
                            </form>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('login') }}"    class="text-sm text-gray-300 hover:text-white transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="text-sm bg-blue-600 hover:bg-blue-500 text-white px-3 py-1.5 rounded-lg transition-colors">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- ===== FLASH MESSAGES ===== --}}
    @if(session('success') || session('error') || session('info'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        @if(session('success'))
        <div class="bg-green-900/50 border border-green-700 text-green-300 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-900/50 border border-red-700 text-red-300 px-4 py-3 rounded-lg text-sm flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
        @endif
        @if(session('info'))
        <div class="bg-blue-900/50 border border-blue-700 text-blue-300 px-4 py-3 rounded-lg text-sm">
            {{ session('info') }}
        </div>
        @endif
    </div>
    @endif

    {{-- ===== MAIN CONTENT ===== --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="bg-gray-900 border-t border-gray-800 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-gray-500 text-sm">© 2024 Epic Games Store Replica — Tugas Besar Sistem Basis Data</p>
                <p class="text-gray-600 text-xs">Laravel 12 · MySQL 8 · Tailwind CSS · USU</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
