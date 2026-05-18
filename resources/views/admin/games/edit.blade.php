@extends('layouts.admin')

@section('title', 'Edit Game — ' . $game->title)
@section('breadcrumb', 'Edit Game')

@section('content')

{{-- Header --}}
<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('admin.games.index') }}"
       class="p-2 rounded-xl text-gray-400 hover:text-white hover:bg-white/10 transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>
    <div class="flex-1 min-w-0">
        <h1 class="text-xl font-bold text-white truncate">Edit: {{ $game->title }}</h1>
        <div class="flex items-center gap-2 mt-0.5">
            <p class="text-gray-500 text-xs">ID: {{ $game->game_id }}</p>
            @if($game->is_active)
            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400">Aktif</span>
            @else
            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-red-500/10 text-red-400">Nonaktif</span>
            @endif
        </div>
    </div>
    {{-- Tombol Lihat di Store --}}
    <a href="{{ route('game.show', $game->game_id) }}" target="_blank"
       class="flex items-center gap-2 border border-[#2a2a30] hover:border-gray-500 text-gray-400 hover:text-white text-sm font-medium px-4 py-2.5 rounded-xl transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
        </svg>
        Lihat di Store
    </a>
</div>

{{-- Error Summary --}}
@if($errors->any())
<div class="bg-red-900/20 border border-red-700/40 rounded-2xl p-4 mb-5">
    <div class="flex items-center gap-2 mb-2">
        <svg class="w-4 h-4 text-red-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        <p class="text-sm font-semibold text-red-300">Terdapat {{ $errors->count() }} kesalahan validasi:</p>
    </div>
    <ul class="space-y-1 pl-6">
        @foreach($errors->all() as $error)
        <li class="text-xs text-red-400 list-disc">{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- Form --}}
<form method="POST" action="{{ route('admin.games.update', $game->game_id) }}">
    @csrf
    @method('PUT')
    @include('admin.games._form')
</form>

{{-- Danger Zone --}}
<div class="mt-6 bg-[#111114] border border-red-900/30 rounded-2xl p-5">
    <h3 class="text-sm font-semibold text-red-400 mb-1">Zona Berbahaya</h3>
    <p class="text-xs text-gray-500 mb-4">
        Menonaktifkan game akan menyembunyikannya dari store. Data transaksi dan library tetap aman.
    </p>
    <form method="POST" action="{{ route('admin.games.destroy', $game->game_id) }}"
          onsubmit="return confirm('Yakin ingin menonaktifkan \'{{ addslashes($game->title) }}\'?')">
        @csrf @method('DELETE')
        <button type="submit"
                class="flex items-center gap-2 bg-red-900/20 hover:bg-red-900/40 border border-red-700/40 hover:border-red-600/60 text-red-400 hover:text-red-300 text-sm font-semibold px-4 py-2.5 rounded-xl transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
            </svg>
            Nonaktifkan Game Ini
        </button>
    </form>
</div>

@endsection