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

    {{-- ===== NAVBAR (component) ===== --}}
    <x-navbar />

    {{-- ===== FLASH MESSAGES ===== --}}
    @if (session('success') || session('error') || session('info'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 space-y-2">
            @if (session('success'))
                <div class="flex items-center gap-2 bg-green-900/50 border border-green-700/60 text-green-300 px-4 py-3 rounded-xl text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="flex items-center gap-2 bg-red-900/50 border border-red-700/60 text-red-300 px-4 py-3 rounded-xl text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    {{ session('error') }}
                </div>
            @endif
            @if (session('info'))
                <div class="flex items-center gap-2 bg-blue-900/50 border border-blue-700/60 text-blue-300 px-4 py-3 rounded-xl text-sm">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10A8 8 0 112 10a8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
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
                <div class="flex items-center gap-2 text-gray-500">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                    <p class="text-sm">© 2024 Epic Games Store Replica — Tugas Besar Sistem Basis Data</p>
                </div>
                <p class="text-gray-600 text-xs">Laravel · MySQL 8 · Tailwind CSS · USU</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>