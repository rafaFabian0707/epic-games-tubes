<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — Epic Games</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @stack('styles')
</head>
<body class="bg-[#0d0d0f] text-white min-h-screen flex antialiased" x-data="{ sidebarOpen: true }">

    {{-- ====================================================== --}}
    {{-- SIDEBAR                                                 --}}
    {{-- ====================================================== --}}
    <aside
        class="fixed top-0 left-0 h-full z-40 flex flex-col bg-[#111114] border-r border-[#1e1e22] transition-all duration-300 ease-in-out"
        :class="sidebarOpen ? 'w-60' : 'w-16'">

        {{-- Logo / Brand --}}
        <div class="flex items-center gap-3 px-4 py-5 border-b border-[#1e1e22] shrink-0">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 min-w-0">
                <svg class="w-8 h-8 shrink-0 text-[#0078f2]" viewBox="0 0 32 38" fill="currentColor">
                    <path d="M16 0L0 5v28l16 5 16-5V5L16 0zm13.1 31.4L16 35.3 2.9 31.4V7.4L16 3.5l13.1 3.9v24z"/>
                    <path d="M9 11h14v3H9zm0 6h14v3H9zm0 6h9v3H9z"/>
                </svg>
                <span class="font-bold text-sm tracking-wide whitespace-nowrap overflow-hidden transition-all duration-300"
                      :class="sidebarOpen ? 'opacity-100 max-w-xs' : 'opacity-0 max-w-0'">
                    Admin Panel
                </span>
            </a>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto py-4 px-2 space-y-0.5">

            {{-- Section: Utama --}}
            <p class="px-3 py-1 text-[10px] font-semibold text-gray-600 uppercase tracking-widest transition-opacity duration-200"
               :class="sidebarOpen ? 'opacity-100' : 'opacity-0'">Utama</p>

            <a href="{{ route('admin.dashboard') }}"
               class="admin-nav-link group {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="admin-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="admin-nav-label" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Dashboard</span>
            </a>

            {{-- Section: Konten --}}
            <p class="px-3 pt-4 pb-1 text-[10px] font-semibold text-gray-600 uppercase tracking-widest transition-opacity duration-200"
               :class="sidebarOpen ? 'opacity-100' : 'opacity-0'">Konten</p>

            <a href="{{ route('admin.games.index') }}"
               class="admin-nav-link group {{ request()->routeIs('admin.games.*') ? 'active' : '' }}">
                <svg class="admin-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
                </svg>
                <span class="admin-nav-label" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Game</span>
            </a>

            <a href="{{ route('admin.news.index') }}"
               class="admin-nav-link group {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
                <svg class="admin-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                <span class="admin-nav-label" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Berita</span>
            </a>

            <a href="{{ route('admin.discounts.index') }}"
               class="admin-nav-link group {{ request()->routeIs('admin.discounts.*') ? 'active' : '' }}">
                <svg class="admin-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <span class="admin-nav-label" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Diskon</span>
            </a>

            <a href="{{ route('admin.platforms.index') }}"
               class="admin-nav-link group {{ request()->routeIs('admin.platforms.*') ? 'active' : '' }}">
                <svg class="admin-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="admin-nav-label" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Platform</span>
            </a>

            {{-- Section: Pengguna --}}
            <p class="px-3 pt-4 pb-1 text-[10px] font-semibold text-gray-600 uppercase tracking-widest transition-opacity duration-200"
               :class="sidebarOpen ? 'opacity-100' : 'opacity-0'">Pengguna</p>

            <a href="{{ route('admin.users.index') }}"
               class="admin-nav-link group {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <svg class="admin-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="admin-nav-label" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Pengguna</span>
            </a>

        </nav>

        {{-- Bottom: User info + Logout --}}
        <div class="border-t border-[#1e1e22] p-3 shrink-0">
            {{-- Kembali ke Store --}}
            <a href="{{ route('home') }}"
               class="admin-nav-link group mb-1 text-gray-500 hover:text-white">
                <svg class="admin-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="admin-nav-label text-xs" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Kembali ke Store</span>
            </a>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="admin-nav-link group w-full text-red-500 hover:text-red-400 hover:bg-red-500/10">
                    <svg class="admin-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="admin-nav-label text-xs" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Logout</span>
                </button>
            </form>

            {{-- Avatar user --}}
            <div class="flex items-center gap-2 mt-3 px-1"
                 x-show="sidebarOpen" x-transition>
                <div class="w-7 h-7 rounded-full bg-[#0078f2] flex items-center justify-center text-xs font-bold shrink-0">
                    {{ strtoupper(substr(auth()->user()->username ?? 'A', 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold truncate">{{ auth()->user()->username }}</p>
                    <p class="text-[10px] text-gray-500 truncate">Administrator</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- ====================================================== --}}
    {{-- MAIN WRAPPER                                            --}}
    {{-- ====================================================== --}}
    <div class="flex-1 flex flex-col min-h-screen transition-all duration-300"
         :class="sidebarOpen ? 'ml-60' : 'ml-16'">

        {{-- TOP BAR --}}
        <header class="sticky top-0 z-30 bg-[#0d0d0f]/80 backdrop-blur-md border-b border-[#1e1e22] flex items-center gap-4 px-6 py-3.5">
            {{-- Sidebar Toggle --}}
            <button @click="sidebarOpen = !sidebarOpen"
                    class="p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Breadcrumb --}}
            <div class="flex-1 flex items-center gap-2 text-sm text-gray-400">
                <span class="text-gray-600">Admin</span>
                <svg class="w-3 h-3 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-white font-medium">@yield('breadcrumb', 'Dashboard')</span>
            </div>

            {{-- Right: user badge --}}
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-[#0078f2] flex items-center justify-center text-sm font-bold">
                    {{ strtoupper(substr(auth()->user()->username ?? 'A', 0, 1)) }}
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success') || session('error') || session('info'))
        <div class="px-6 pt-4 space-y-2">
            @if(session('success'))
            <div class="flex items-center gap-2 bg-green-900/40 border border-green-700/50 text-green-300 px-4 py-3 rounded-xl text-sm">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="flex items-center gap-2 bg-red-900/40 border border-red-700/50 text-red-300 px-4 py-3 rounded-xl text-sm">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
            @endif
            @if(session('info'))
            <div class="flex items-center gap-2 bg-blue-900/40 border border-blue-700/50 text-blue-300 px-4 py-3 rounded-xl text-sm">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10A8 8 0 112 10a8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                {{ session('info') }}
            </div>
            @endif
        </div>
        @endif

        {{-- PAGE CONTENT --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>

        {{-- FOOTER --}}
        <footer class="border-t border-[#1e1e22] px-6 py-4">
            <p class="text-xs text-gray-600 text-center">
                Epic Games Store Admin Panel — Tugas Besar Sistem Basis Data · Laravel · MySQL 8 · Tailwind CSS
            </p>
        </footer>
    </div>

    {{-- Global Styles --}}
    <style>
        .admin-nav-link {
            @apply flex items-center gap-3 px-3 py-2 rounded-lg text-gray-400 text-sm font-medium
                   hover:bg-white/5 hover:text-white transition-all duration-150 cursor-pointer w-full;
        }
        .admin-nav-link.active {
            @apply bg-[#0078f2]/15 text-[#4da3ff] border border-[#0078f2]/20;
        }
        .admin-nav-icon {
            @apply w-5 h-5 shrink-0;
        }
        .admin-nav-label {
            @apply whitespace-nowrap transition-all duration-200;
        }
        /* Scrollbar styling */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #2a2a30; border-radius: 2px; }
    </style>

    @stack('scripts')
</body>
</html>
