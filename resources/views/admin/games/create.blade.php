@extends('layouts.admin')

@section('title', 'Tambah Game Baru')
@section('breadcrumb', 'Tambah Game')

@section('content')

{{-- Header --}}
<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('admin.games.index') }}"
       class="p-2 rounded-xl text-gray-400 hover:text-white hover:bg-white/10 transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>
    <div>
        <h1 class="text-xl font-bold text-white">Tambah Game Baru</h1>
        <p class="text-gray-500 text-xs mt-0.5">Isi semua informasi game yang akan ditambahkan ke store</p>
    </div>
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
<form method="POST" action="{{ route('admin.games.store') }}">
    @csrf
    @include('admin.games._form')
</form>

@endsection