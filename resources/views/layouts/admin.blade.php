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
<body class="bg-[#0d0d0f] text-white min-h-screen antialiased" x-data="{ sidebarOpen: true }">

<div class="flex min-h-screen">

    {{-- ====================================================== --}}
    {{-- SIDEBAR                                                 --}}
    {{-- ====================================================== --}}
    <aside class="fixed top-0 left-0 h-full z-40 flex flex-col bg-[#111114] border-r border-[#1e1e22] transition-all duration-300"
           :class="sidebarOpen ? 'w-60' : 'w-16'">

        {{-- Brand --}}
        <div class="flex items-center gap-3 px-4 py-[18px] border-b border-[#1e1e22] shrink-0 overflow-hidden">
            {{-- Logo SVG --}}
            <svg class="w-7 h-7 shrink-0 text-[#0078f2]" viewBox="0 0 32 38" fill="currentColor">
                <path d="M16 0L0 5v28l16 5 16-5V5L16 0zm13.1 31.4L16 35.3 2.9 31.4V7.4L16 3.5l13.1 3.9v24z"/>
                <path d="M9 11h14v3H9zm0 6h14v3H9zm0 6h9v3H9z"/>
            </svg>
            <span class="font-bold text-sm tracking-wide whitespace-nowrap transition-all duration-200 overflow-hidden"
                  :class="sidebarOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0'">
                Admin Panel
            </span>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto py-3 px-2 space-y-0.5">

            {{-- Label seksi --}}
            <p class="px-3 py-1.5 text-[10px] font-semibold text-gray-600 uppercase tracking-widest overflow-hidden transition-all duration-200"
               :class="sidebarOpen ? 'opacity-100 h-auto' : 'opacity-0 h-0 py-0'">Utama</p>

            <a href="{{ route('admin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               :title="!sidebarOpen ? 'Dashboard' : ''">
                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="sidebar-label" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Dashboard</span>
            </a>

            <p class="px-3 pt-4 pb-1.5 text-[10px] font-semibold text-gray-600 uppercase tracking-widest overflow-hidden transition-all duration-200"
               :class="sidebarOpen ? 'opacity-100 h-auto' : 'opacity-0 h-0 py-0 pt-0'">Konten</p>

            <a href="{{ route('admin.games.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.games.*') ? 'active' : '' }}"
               :title="!sidebarOpen ? 'Game' : ''">
                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
                </svg>
                <span class="sidebar-label" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Game</span>
            </a>

            <a href="{{ route('admin.news.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.news.*') ? 'active' : '' }}"
               :title="!sidebarOpen ? 'Berita' : ''">
                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                <span class="sidebar-label" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Berita</span>
            </a>

            <a href="{{ route('admin.discounts.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.discounts.*') ? 'active' : '' }}"
               :title="!sidebarOpen ? 'Diskon' : ''">
                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <span class="sidebar-label" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Diskon</span>
            </a>

            <p class="px-3 pt-4 pb-1.5 text-[10px] font-semibold text-gray-600 uppercase tracking-widest overflow-hidden transition-all duration-200"
               :class="sidebarOpen ? 'opacity-100 h-auto' : 'opacity-0 h-0 py-0 pt-0'">Pengguna</p>

            <a href="{{ route('admin.users.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
               :title="!sidebarOpen ? 'Pengguna' : ''">
                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="sidebar-label" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Pengguna</span>
            </a>

        </nav>

        {{-- Bottom --}}
        <div class="border-t border-[#1e1e22] px-2 pt-2 pb-3 shrink-0 space-y-0.5">

            <a href="{{ route('home') }}"
               class="sidebar-link text-gray-500 hover:text-white"
               :title="!sidebarOpen ? 'Kembali ke Store' : ''">
                <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span class="sidebar-label text-xs" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Kembali ke Store</span>
            </a>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit"
                        class="sidebar-link w-full text-red-500 hover:text-red-400 hover:bg-red-500/10"
                        :title="!sidebarOpen ? 'Logout' : ''">
                    <svg class="sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    <span class="sidebar-label text-xs" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 overflow-hidden'">Logout</span>
                </button>
            </form>

            {{-- Avatar --}}
            <div class="flex items-center gap-2.5 px-2 pt-2 mt-1 border-t border-[#1e1e22] overflow-hidden">
                <div class="w-7 h-7 rounded-full bg-[#0078f2] flex items-center justify-center text-xs font-bold shrink-0">
                    {{ strtoupper(substr(auth()->user()->username ?? 'A', 0, 1)) }}
                </div>
                <div class="min-w-0 transition-all duration-200 overflow-hidden"
                     :class="sidebarOpen ? 'opacity-100 w-auto' : 'opacity-0 w-0'">
                    <p class="text-xs font-semibold truncate leading-tight">{{ auth()->user()->username }}</p>
                    <p class="text-[10px] text-gray-500 leading-tight">Administrator</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- ====================================================== --}}
    {{-- MAIN                                                    --}}
    {{-- ====================================================== --}}
    <div class="flex-1 flex flex-col min-h-screen transition-all duration-300"
         :class="sidebarOpen ? 'ml-60' : 'ml-16'">

        {{-- TOP BAR --}}
        <header class="sticky top-0 z-30 bg-[#0d0d0f]/90 backdrop-blur border-b border-[#1e1e22] flex items-center gap-4 px-6 h-14 shrink-0">
            <button @click="sidebarOpen = !sidebarOpen"
                    class="p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-white/10 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="flex-1 flex items-center gap-2 text-sm">
                <span class="text-gray-600 text-xs">Admin</span>
                <svg class="w-3 h-3 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-white text-sm font-medium">@yield('breadcrumb', 'Dashboard')</span>
            </div>
            <div class="w-8 h-8 rounded-full bg-[#0078f2] flex items-center justify-center text-sm font-bold shrink-0">
                {{ strtoupper(substr(auth()->user()->username ?? 'A', 0, 1)) }}
            </div>
        </header>

        {{-- Flash --}}
        @if(session('success') || session('error') || session('info'))
        <div class="px-6 pt-4 space-y-2">
            @if(session('success'))
            <div class="flex items-center gap-2 bg-emerald-900/30 border border-emerald-700/40 text-emerald-300 px-4 py-3 rounded-xl text-sm">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="flex items-center gap-2 bg-red-900/30 border border-red-700/40 text-red-300 px-4 py-3 rounded-xl text-sm">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
            @endif
            @if(session('info'))
            <div class="flex items-center gap-2 bg-blue-900/30 border border-blue-700/40 text-blue-300 px-4 py-3 rounded-xl text-sm">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10A8 8 0 112 10a8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                {{ session('info') }}
            </div>
            @endif
        </div>
        @endif

        {{-- CONTENT --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>

        <footer class="border-t border-[#1e1e22] px-6 py-3">
            <p class="text-xs text-gray-700 text-center">Epic Games Store Admin · Laravel · MySQL 8 · Tailwind CSS</p>
        </footer>
    </div>
</div>

<style>
    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0.75rem;
        border-radius: 0.625rem;
        color: rgb(156 163 175);
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.15s;
        cursor: pointer;
        width: 100%;
        text-align: left;
        background: transparent;
        border: none;
        text-decoration: none;
        white-space: nowrap;
        overflow: hidden;
    }
    .sidebar-link:hover {
        background: rgba(255,255,255,0.05);
        color: white;
    }
    .sidebar-link.active {
        background: rgba(0,120,242,0.12);
        color: #60a5fa;
        border: 1px solid rgba(0,120,242,0.2);
    }
    .sidebar-icon {
        width: 1.125rem;
        height: 1.125rem;
        flex-shrink: 0;
    }
    .sidebar-label {
        transition: opacity 0.2s, width 0.2s;
        white-space: nowrap;
    }
    ::-webkit-scrollbar { width: 4px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #2a2a30; border-radius: 2px; }
</style>

@stack('scripts')
</body>
</html>