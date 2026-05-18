<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login — Epic Games</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#0d0d0f] text-white min-h-screen flex items-center justify-center px-4 antialiased">

    <div class="w-full max-w-[400px]" x-data="{ showPw: false }">

        {{-- Logo --}}
        <div class="flex flex-col items-center mb-8">
            <div class="flex items-center gap-3 mb-3">
                <svg class="w-10 h-12 text-[#0078f2]" viewBox="0 0 32 38" fill="currentColor">
                    <path d="M16 0L0 5v28l16 5 16-5V5L16 0zm13.1 31.4L16 35.3 2.9 31.4V7.4L16 3.5l13.1 3.9v24z"/>
                    <path d="M9 11h14v3H9zm0 6h14v3H9zm0 6h9v3H9z"/>
                </svg>
                <span class="text-xl font-bold tracking-tight">Epic Games</span>
            </div>
            <div class="flex items-center gap-2 bg-[#0078f2]/10 border border-[#0078f2]/30 text-[#4da3ff] text-xs px-3 py-1.5 rounded-full font-medium">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Admin Panel Access
            </div>
        </div>

        {{-- Card --}}
        <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-8 shadow-2xl">

            <h1 class="text-lg font-bold text-white text-center mb-1">Masuk sebagai Admin</h1>
            <p class="text-gray-500 text-xs text-center mb-6">Akses terbatas untuk administrator sistem</p>

            {{-- Error global --}}
            @if($errors->any())
            <div class="flex items-center gap-2 bg-red-900/30 border border-red-700/40 text-red-300 px-4 py-3 rounded-xl text-sm mb-4">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $errors->first() }}
            </div>
            @endif

            {{-- Session error (dari redirect admin middleware) --}}
            @if(session('error'))
            <div class="flex items-center gap-2 bg-red-900/30 border border-red-700/40 text-red-300 px-4 py-3 rounded-xl text-sm mb-4">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-gray-400">Email Administrator</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="admin@epicgames.com"
                        required
                        autofocus
                        class="w-full bg-[#0d0d0f] border {{ $errors->has('email') ? 'border-red-500' : 'border-[#2a2a30]' }}
                               rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600
                               focus:outline-none focus:border-[#0078f2] focus:ring-1 focus:ring-[#0078f2]/50
                               transition-all">
                    @error('email')
                    <p class="text-red-400 text-xs">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="space-y-1.5">
                    <label class="text-xs font-medium text-gray-400">Kata Sandi</label>
                    <div class="relative">
                        <input
                            :type="showPw ? 'text' : 'password'"
                            name="password"
                            placeholder="••••••••"
                            required
                            class="w-full bg-[#0d0d0f] border border-[#2a2a30] rounded-xl px-4 py-3 pr-11 text-sm text-white placeholder-gray-600
                                   focus:outline-none focus:border-[#0078f2] focus:ring-1 focus:ring-[#0078f2]/50 transition-all">
                        <button
                            type="button"
                            @click="showPw = !showPw"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-500 hover:text-gray-300 transition-colors">
                            <svg x-show="!showPw" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPw" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember"
                           class="w-4 h-4 bg-[#0d0d0f] border border-[#2a2a30] rounded text-[#0078f2] focus:ring-[#0078f2]/50 cursor-pointer">
                    <label for="remember" class="text-xs text-gray-400 cursor-pointer select-none">Ingat saya di perangkat ini</label>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full bg-[#0078f2] hover:bg-[#0063cc] text-white font-semibold py-3 px-4 rounded-xl
                           transition-all duration-200 text-sm mt-2 flex items-center justify-center gap-2
                           focus:outline-none focus:ring-2 focus:ring-[#0078f2]/50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Masuk ke Admin Panel
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-3 my-5">
                <div class="flex-1 h-px bg-[#1e1e22]"></div>
                <span class="text-xs text-gray-600">atau</span>
                <div class="flex-1 h-px bg-[#1e1e22]"></div>
            </div>

            <a href="{{ route('login') }}"
               class="flex items-center justify-center gap-2 text-sm text-gray-400 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke login pengguna
            </a>
        </div>

        {{-- Footer note --}}
        <p class="text-center text-xs text-gray-700 mt-6">
            Akses tidak sah akan dicatat dan dilaporkan.
        </p>
    </div>

</body>
</html>
