@extends('layouts.app')
@section('title', 'Masuk ke Epic Games Store')

@section('content')
<div class="min-h-screen bg-[#121212] flex flex-col items-center justify-center px-4 py-12 text-white antialiased">
    
    <div class="w-full max-w-[440px] bg-[#18181c] border border-[#2a2a2e] rounded-[12px] p-10 shadow-2xl">
        
        <div class="flex justify-center mb-5">
            <svg class="w-10 h-12 text-white" viewBox="0 0 32 38" fill="currentColor">
                <path d="M16 0L0 5v28l16 5 16-5V5L16 0zm13.1 31.4L16 35.3l-13.1-3.9V7.4L16 3.5l13.1 3.9v24z"/>
                <path d="M9 11h14v3H9zm0 6h14v3H9zm0 6h9v3H9z"/>
            </svg>
        </div>

        <h1 class="text-[18px] font-bold tracking-tight text-center mb-6">Masuk ke Epic Games</h1>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div class="space-y-1.5">
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Alamat email" required autofocus
                       class="w-full bg-[#202024] border {{ $errors->has('email') ? 'border-red-500' : 'border-[#404044]' }} rounded-[4px] px-3 py-3 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-gray-400 transition-colors">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5">
                <div class="relative">
                    <input type="password" id="password" name="password" placeholder="Kata sandi" class="w-full bg-[#202024] border border-[#404044] rounded-[4px] px-3 py-3 pr-10 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-gray-400 transition-colors" required>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">
                        <button type="button" onclick="togglePasswordVisibility()" class="hover:text-white focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center cursor-pointer group select-none">
                    <input type="checkbox" name="remember" id="remember" class="sr-only peer">
                    <div class="w-4 h-4 border border-[#404044] rounded-[2px] bg-[#202024] flex flex-shrink-0 justify-center items-center mr-2 peer-checked:bg-blue-500 peer-checked:border-blue-500 transition-all">
                        <svg class="w-2.5 h-2.5 text-white stroke-[3px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-xs text-gray-400 group-hover:text-gray-300">Ingat saya</span>
                </label>
                <a href="#" class="text-xs text-[#00a8ff] hover:underline">Lupa kata sandi?</a>
            </div>

            <button type="submit"
                    class="w-full bg-[#00a8ff] hover:bg-[#0096e0] text-black font-bold py-3 rounded-[4px] text-xs uppercase tracking-wider transition-colors duration-200 mt-2">
                Lanjutkan
            </button>
        </form>

        <p class="text-xs text-gray-400 text-center mt-4">
            Baru bergabung? <a href="{{ route('register') }}" class="text-[#00a8ff] underline hover:text-blue-300 ml-1">Buat akun</a>
        </p>

        <div class="my-6 border-t border-[#2a2a2e] relative">
            <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-[#18181c] px-3 text-[10px] uppercase tracking-widest text-gray-400 font-bold whitespace-nowrap">
                Hanya dimainkan di konsol?
            </span>
        </div>
        <p class="text-[11px] text-gray-400 text-center mb-4">Masuk untuk mengakses kemajuan dan pembelian</p>

        <div class="grid grid-cols-3 gap-2 mb-6">
            <button class="bg-[#202024] hover:bg-[#2a2a30] border border-[#303034] py-3 rounded-[4px] flex flex-col items-center justify-center transition-colors">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">PlayStation</span>
            </button>
            <button class="bg-[#202024] hover:bg-[#2a2a30] border border-[#303034] py-3 rounded-[4px] flex flex-col items-center justify-center transition-colors">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Xbox</span>
            </button>
            <button class="bg-[#202024] hover:bg-[#2a2a30] border border-[#303034] py-3 rounded-[4px] flex flex-col items-center justify-center transition-colors">
                <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Nintendo</span>
            </button>
        </div>

        <div class="my-4 border-t border-[#2a2a2e] relative">
            <span class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-[#18181c] px-3 text-[10px] uppercase tracking-widest text-gray-400 font-bold whitespace-nowrap">
                Cara masuk lain
            </span>
        </div>

        <div class="grid grid-cols-3 gap-2 mb-6">
            <button class="bg-[#202024] hover:bg-[#2a2a30] border border-[#303034] py-2.5 rounded-[4px] text-[11px] font-medium tracking-wide">Google</button>
            <button class="bg-[#202024] hover:bg-[#2a2a30] border border-[#303034] py-2.5 rounded-[4px] text-[11px] font-medium tracking-wide">Steam</button>
            <button class="bg-[#202024] hover:bg-[#2a2a30] border border-[#303034] py-2.5 rounded-[4px] text-[11px] font-medium tracking-wide">Apple</button>
        </div>

        <div class="mt-6 text-center space-y-3">
            <div>
                <a href="#" class="text-xs text-[#00a8ff] underline hover:text-blue-300 tracking-wide">Ada kendala masuk?</a>
            </div>
            <div>
                <a href="#" class="text-xs text-white underline hover:text-gray-200 tracking-wide">Kebijakan Privasi</a>
            </div>
        </div>

    </div>
</div>

<script>
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
    } else {
        passwordInput.type = 'password';
    }
}
</script>
@endsection