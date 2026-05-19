@extends('layouts.admin')
@section('title','Kelola Berita')
@section('breadcrumb','Kelola Berita')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
    <div>
        <h1 class="text-xl font-bold text-white">Kelola Berita</h1>
        <p class="text-gray-500 text-xs mt-0.5">Total <span class="text-white font-semibold">{{ $newsList->total() }}</span> artikel</p>
    </div>
    <a href="{{ route('admin.news.create') }}"
       class="flex items-center gap-2 bg-[#0078f2] hover:bg-[#0063cc] text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all shadow-lg shadow-[#0078f2]/20 self-start sm:self-auto whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tulis Berita
    </a>
</div>

{{-- Filter --}}
<div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-4 mb-5">
    <form method="GET" action="{{ route('admin.news.index') }}" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1 relative">
            <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Cari judul atau publisher..."
                   class="adm-input pl-10">
        </div>
        <select name="status" class="adm-input sm:w-40">
            <option value="">Semua Status</option>
            <option value="active"   {{ request('status')==='active'   ?'selected':'' }}>Aktif</option>
            <option value="inactive" {{ request('status')==='inactive' ?'selected':'' }}>Nonaktif</option>
        </select>
        <button type="submit" class="bg-[#0078f2] hover:bg-[#0063cc] text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all whitespace-nowrap">
            Cari
        </button>
        @if(request()->hasAny(['q','status']))
        <a href="{{ route('admin.news.index') }}"
           class="border border-[#2a2a30] hover:border-gray-500 text-gray-400 hover:text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-all whitespace-nowrap text-center">
            Reset
        </a>
        @endif
    </form>
</div>

{{-- Grid artikel --}}
@if($newsList->isEmpty())
<div class="bg-[#111114] border border-[#1e1e22] rounded-2xl flex flex-col items-center justify-center py-20 text-gray-600">
    <svg class="w-12 h-12 mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
    </svg>
    <p class="text-sm">Tidak ada berita ditemukan.</p>
    <a href="{{ route('admin.news.create') }}" class="mt-2 text-xs text-[#60a5fa] hover:underline">+ Tulis berita pertama</a>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-5">
    @foreach($newsList as $news)
    <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl overflow-hidden flex flex-col hover:border-[#2a2a30] transition-colors group">
        {{-- Cover --}}
        <div class="relative h-40 bg-[#0d0d0f] overflow-hidden shrink-0">
            @if($news->cover_url)
            <img src="{{ $news->cover_url }}" alt="{{ $news->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                 onerror="this.style.display='none'">
            @endif
            {{-- Status badge --}}
            <div class="absolute top-2.5 right-2.5">
                @if($news->is_active)
                <span class="text-[10px] font-semibold px-2 py-1 rounded-full bg-emerald-500/80 text-white backdrop-blur-sm">Aktif</span>
                @else
                <span class="text-[10px] font-semibold px-2 py-1 rounded-full bg-red-500/80 text-white backdrop-blur-sm">Nonaktif</span>
                @endif
            </div>
        </div>

        {{-- Body --}}
        <div class="p-4 flex flex-col flex-1">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-[10px] text-gray-500">{{ $news->publisher }}</span>
                @if($news->date)
                <span class="text-[10px] text-gray-600">· {{ $news->date }}</span>
                @endif
            </div>
            <h3 class="text-sm font-semibold text-white leading-snug mb-1 line-clamp-2 group-hover:text-[#60a5fa] transition-colors">
                {{ $news->title }}
            </h3>
            @if($news->main_content)
            <p class="text-[11px] text-gray-500 line-clamp-2 flex-1">{{ $news->excerpt }}</p>
            @else
            <div class="flex-1"></div>
            @endif

            {{-- Actions --}}
            <div class="flex items-center justify-between mt-4 pt-3 border-t border-[#1e1e22]">
                <div class="flex items-center gap-1">
                    {{-- Lihat --}}
                    <a href="{{ route('news.show', $news->news_id) }}" target="_blank"
                       title="Lihat artikel"
                       class="p-1.5 rounded-lg text-gray-500 hover:text-gray-300 hover:bg-white/10 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </a>
                    {{-- Edit --}}
                    <a href="{{ route('admin.news.edit', $news->news_id) }}"
                       title="Edit"
                       class="p-1.5 rounded-lg text-gray-500 hover:text-[#60a5fa] hover:bg-[#0078f2]/10 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                </div>
                {{-- Toggle status --}}
                @if($news->is_active)
                <form method="POST" action="{{ route('admin.news.destroy', $news->news_id) }}"
                      onsubmit="return confirm('Nonaktifkan berita ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" title="Nonaktifkan"
                            class="flex items-center gap-1.5 text-[11px] text-gray-500 hover:text-red-400 hover:bg-red-500/10 px-2.5 py-1.5 rounded-lg transition-all">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        Nonaktifkan
                    </button>
                </form>
                @else
                <form method="POST" action="{{ route('admin.news.restore', $news->news_id) }}"
                      onsubmit="return confirm('Aktifkan kembali berita ini?')">
                    @csrf @method('PATCH')
                    <button type="submit" title="Aktifkan kembali"
                            class="flex items-center gap-1.5 text-[11px] text-gray-500 hover:text-emerald-400 hover:bg-emerald-500/10 px-2.5 py-1.5 rounded-lg transition-all">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Aktifkan
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Pagination --}}
@if($newsList->hasPages())
<div class="flex items-center justify-between gap-4 flex-wrap">
    <p class="text-xs text-gray-500">
        Menampilkan <span class="text-white">{{ $newsList->firstItem() }}–{{ $newsList->lastItem() }}</span>
        dari <span class="text-white">{{ $newsList->total() }}</span> artikel
    </p>
    <div class="flex items-center gap-1">
        @if($newsList->onFirstPage())
            <span class="px-btn text-gray-600 cursor-not-allowed">← Prev</span>
        @else
            <a href="{{ $newsList->previousPageUrl() }}" class="px-btn text-gray-400 hover:text-white hover:bg-[#1e1e22]">← Prev</a>
        @endif
        @foreach($newsList->getUrlRange(max(1,$newsList->currentPage()-2),min($newsList->lastPage(),$newsList->currentPage()+2)) as $page=>$url)
        <a href="{{ $url }}" class="px-btn {{ $page===$newsList->currentPage()?'bg-[#0078f2] text-white font-semibold':'text-gray-400 hover:text-white hover:bg-[#1e1e22]' }}">{{ $page }}</a>
        @endforeach
        @if($newsList->hasMorePages())
            <a href="{{ $newsList->nextPageUrl() }}" class="px-btn text-gray-400 hover:text-white hover:bg-[#1e1e22]">Next →</a>
        @else
            <span class="px-btn text-gray-600 cursor-not-allowed">Next →</span>
        @endif
    </div>
</div>
@endif
@endif

@push('styles')
<style>
    .adm-input { @apply w-full bg-[#0d0d0f] border border-[#2a2a30] rounded-xl px-3.5 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-[#0078f2] focus:ring-1 focus:ring-[#0078f2]/30 transition-all; }
    .px-btn { @apply px-3 py-1.5 rounded-lg text-xs bg-[#0d0d0f] transition-all; }
    select.adm-input option { background-color: #111114; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>
@endpush

@endsection
