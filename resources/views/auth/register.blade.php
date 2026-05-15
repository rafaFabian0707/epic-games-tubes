@extends('layouts.app')
@section('title', 'Daftar Akun')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-sm">

        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold">Buat Akun Baru</h1>
            <p class="text-gray-400 text-sm mt-2">Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300">Masuk</a>
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm text-gray-400 mb-1.5">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required
                       class="w-full bg-gray-800 border {{ $errors->has('username') ? 'border-red-500' : 'border-gray-700' }} rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 transition-colors">
                @error('username') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1.5">Nama Lengkap <span class="text-gray-600">(opsional)</span></label>
                <input type="text" name="full_name" value="{{ old('full_name') }}"
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 transition-colors">
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full bg-gray-800 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-700' }} rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 transition-colors">
                @error('email') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1.5">Password</label>
                <input type="password" name="password" required
                       class="w-full bg-gray-800 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-700' }} rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 transition-colors">
                @error('password') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-1.5">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full bg-gray-800 border border-gray-700 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-blue-500 transition-colors">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-2.5 rounded-xl transition-colors">
                Buat Akun
            </button>
        </form>
    </div>
</div>
@endsection
