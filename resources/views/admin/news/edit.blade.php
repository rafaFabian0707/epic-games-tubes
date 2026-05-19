@extends('layouts.admin')
@section('title','Edit Berita — ' . $news->title)
@section('breadcrumb','Edit Berita')

@section('content')
<div class="flex items-center gap-4 mb-6">
    <a href="{{ route('admin.news.index') }}"
       class="p-2 rounded-xl text-gray-400 hover:text-white hover:bg-white/10 transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>
    <div class="flex-1 min-w-0">
        <h1 class="text-xl font-bold text-white truncate">Edit: {{ $news->title }}</h1>
        <div class="flex items-center gap-2 mt-0.5">
            <p class="text-gray-500 text-xs">ID: {{ $news->news_id }}</p>
            @if($news->is_active)
            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400">Aktif</span>
            @else
            <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-red-500/10 text-red-400">Nonaktif</span>
            @endif
        </div>
    </div>
    <a href="{{ route('news.show', $news->news_id) }}" target="_blank"
       class="flex items-center gap-2 border border-[#2a2a30] hover:border-gray-500 text-gray-400 hover:text-white text-sm font-medium px-4 py-2.5 rounded-xl transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
        </svg>
        Lihat Artikel
    </a>
</div>

@if($errors->any())
<div class="bg-red-900/20 border border-red-700/40 rounded-2xl p-4 mb-5">
    <p class="text-sm font-semibold text-red-300 mb-2">{{ $errors->count() }} kesalahan validasi:</p>
    <ul class="space-y-1 pl-5">
        @foreach($errors->all() as $e)
        <li class="text-xs text-red-400 list-disc">{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('admin.news.update', $news->news_id) }}">
    @csrf @method('PUT')
    @include('admin.news._form')
</form>
@endsection
