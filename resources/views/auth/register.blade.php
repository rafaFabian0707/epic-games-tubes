@extends('layouts.app')
@section('title', 'Buat akun - Epic Games')

@section('content')
<div class="min-h-screen bg-[#121212] flex flex-col items-center justify-center px-4 py-12 text-white antialiased">
    
    <div class="w-full max-w-[440px] bg-[#18181c] border border-[#2a2a2e] rounded-[12px] p-10 shadow-2xl">
        
        <div class="mb-6">
            <a href="{{ route('login') }}" class="inline-flex items-center text-xs font-semibold text-gray-400 hover:text-white transition-colors tracking-wide">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>
        </div>

        <h1 class="text-[22px] font-bold tracking-tight mb-6">Buat akun</h1>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-gray-400 tracking-wide">Alamat email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full bg-[#202024] border {{ $errors->has('email') ? 'border-red-500' : 'border-[#404044]' }} rounded-[4px] px-3 py-2.5 text-sm text-white focus:outline-none focus:border-gray-400 transition-colors">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-gray-400 tracking-wide">Nama depan</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}"
                           class="w-full bg-[#202024] border border-[#404044] rounded-[4px] px-3 py-2.5 text-sm text-white focus:outline-none focus:border-gray-400 transition-colors">
                </div>
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-gray-400 tracking-wide">Nama belakang</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}"
                           class="w-full bg-[#202024] border border-[#404044] rounded-[4px] px-3 py-2.5 text-sm text-white focus:outline-none focus:border-gray-400 transition-colors">
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-gray-400 tracking-wide">Buat kata sandi</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required
                           class="w-full bg-[#202024] border {{ $errors->has('password') ? 'border-red-500' : 'border-[#404044]' }} rounded-[4px] px-3 py-2.5 pr-10 text-sm text-white focus:outline-none focus:border-gray-400 transition-colors">
                    
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">
                        <button type="button" onclick="togglePasswordVisibility('password')" class="hover:text-white focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-gray-400 tracking-wide">Konfirmasi kata sandi</label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full bg-[#202024] border {{ $errors->has('password') ? 'border-red-500' : 'border-[#404044]' }} rounded-[4px] px-3 py-2.5 pr-10 text-sm text-white focus:outline-none focus:border-gray-400 transition-colors">
                    
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">
                        <button type="button" onclick="togglePasswordVisibility('password_confirmation')" class="hover:text-white focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="space-y-1.5">
                <div class="flex justify-between items-center">
                    <label class="text-xs font-semibold text-gray-400 tracking-wide">Nama Tampilan</label>
                    <span class="text-[11px] text-gray-500 font-medium">0/16</span>
                </div>
                <div class="relative">
                    <input type="text" name="username" value="{{ old('username') }}" required
                           class="w-full bg-[#202024] border {{ $errors->has('username') ? 'border-red-500' : 'border-[#404044]' }} rounded-[4px] px-3 py-2.5 pr-10 text-sm text-white focus:outline-none focus:border-gray-400 transition-colors">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4 cursor-pointer hover:text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 10H18"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-[11px] text-gray-500 mt-1 leading-normal">Gunakan huruf, angka, garis bawah (_), tanda hubung (-), dan titik (.).</p>
                @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="space-y-4 pt-2">
                <label class="flex items-start cursor-pointer group select-none">
                    <input type="checkbox" name="terms" required class="sr-only peer">
                    <div class="w-5 h-5 border border-[#404044] rounded-[4px] bg-[#202024] flex flex-shrink-0 justify-center items-center mr-3 peer-checked:bg-blue-500 peer-checked:border-blue-500 transition-all">
                        <svg class="w-3 h-3 text-white stroke-[3px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-xs text-gray-400 leading-relaxed group-hover:text-gray-300">
                        Saya telah membaca dan menyetujui <a href="#" class="underline text-white hover:text-gray-200">Syarat Layanan</a> dan <a href="#" class="underline text-white hover:text-gray-200">Perjanjian Lisensi Pengguna Akhir Epic Games Store</a>
                    </span>
                </label>

                <label class="flex items-start cursor-pointer group select-none">
                    <input type="checkbox" name="newsletter" class="sr-only peer">
                    <div class="w-5 h-5 border border-[#404044] rounded-[4px] bg-[#202024] flex flex-shrink-0 justify-center items-center mr-3 peer-checked:bg-blue-500 peer-checked:border-blue-500 transition-all">
                        <svg class="w-3 h-3 text-white stroke-[3px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-xs text-gray-400 leading-relaxed group-hover:text-gray-300">
                        Kirimkan berita, survei, dan penawaran dari Epic Games <span class="text-gray-500">(Opsional)</span>
                    </span>
                </label>
            </div>

            <button type="submit"
                    class="w-full bg-[#ffffff]/10 hover:bg-blue-500 text-white font-bold py-3 rounded-[4px] text-xs uppercase tracking-wider transition-colors duration-200 mt-4 disabled:opacity-50">
                Lanjutkan
            </button>
        </form>

        <div class="mt-8 text-center space-y-4">
            <p class="text-xs text-gray-400">
                Sudah memiliki akun? <a href="{{ route('login') }}" class="text-white underline hover:text-gray-200 ml-1">Masuk</a>
            </p>
            <div>
                <a href="#" class="text-xs text-white underline hover:text-gray-200 tracking-wide">Kebijakan Privasi</a>
            </div>
        </div>

    </div>
</div>

<script>
function togglePasswordVisibility(id) {
    const passwordInput = document.getElementById(id);
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
    } else {
        passwordInput.type = 'password';
    }
}
</script>
@endsection