@extends('layouts.admin')
@section('title','Kelola Diskon')
@section('breadcrumb','Kelola Diskon')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
    <div>
        <h1 class="text-xl font-bold text-white">Kelola Diskon</h1>
        <p class="text-gray-500 text-xs mt-0.5">Total <span class="text-white font-semibold">{{ $discounts->total() }}</span> diskon</p>
    </div>
    <a href="{{ route('admin.discounts.create') }}"
       class="flex items-center gap-2 bg-[#0078f2] hover:bg-[#0063cc] text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all shadow-lg shadow-[#0078f2]/20 self-start sm:self-auto whitespace-nowrap">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Buat Diskon
    </a>
</div>

{{-- Filter --}}
<div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-4 mb-5">
    <form method="GET" action="{{ route('admin.discounts.index') }}" class="flex flex-col sm:flex-row gap-3">
        <div class="flex-1 relative">
            <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Cari judul game..."
                   class="adm-input pl-10">
        </div>
        <select name="status" class="adm-input sm:w-44">
            <option value="">Semua Status</option>
            <option value="active"   {{ request('status')==='active'   ?'selected':'' }}>Aktif Sekarang</option>
            <option value="upcoming" {{ request('status')==='upcoming' ?'selected':'' }}>Akan Datang</option>
            <option value="expired"  {{ request('status')==='expired'  ?'selected':'' }}>Kadaluarsa</option>
            <option value="inactive" {{ request('status')==='inactive' ?'selected':'' }}>Dinonaktifkan</option>
        </select>
        <button type="submit" class="bg-[#0078f2] hover:bg-[#0063cc] text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all whitespace-nowrap">
            Cari
        </button>
        @if(request()->hasAny(['q','status']))
        <a href="{{ route('admin.discounts.index') }}"
           class="border border-[#2a2a30] hover:border-gray-500 text-gray-400 hover:text-white text-sm font-medium px-5 py-2.5 rounded-xl transition-all whitespace-nowrap text-center">
            Reset
        </a>
        @endif
    </form>
</div>

{{-- Tabel --}}
<div class="bg-[#111114] border border-[#1e1e22] rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-[#1e1e22] bg-[#0d0d0f]">
                    <th class="tbl-th">Game</th>
                    <th class="tbl-th text-center">Diskon</th>
                    <th class="tbl-th hidden sm:table-cell">Harga Asli</th>
                    <th class="tbl-th hidden md:table-cell">Mulai</th>
                    <th class="tbl-th hidden md:table-cell">Berakhir</th>
                    <th class="tbl-th">Status</th>
                    <th class="tbl-th text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#1a1a1e]">
                @forelse($discounts as $disc)
                @php
                    $now = now();
                    $isRunning = $disc->is_active && $disc->start_date <= $now && $disc->end_date >= $now;
                    $isUpcoming = $disc->is_active && $disc->start_date > $now;
                    $isExpired  = $disc->end_date < $now;
                @endphp
                <tr class="hover:bg-white/[0.02] transition-colors group">

                    {{-- Game --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $disc->game->cover_image_url ?? 'https://placehold.co/40x56/18181c/fff?text=?' }}"
                                 alt="{{ $disc->game->title ?? '' }}"
                                 class="w-8 h-11 object-cover rounded-md shrink-0 ring-1 ring-white/10"
                                 onerror="this.src='https://placehold.co/40x56/18181c/fff?text=?'">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-white truncate max-w-[180px] group-hover:text-[#60a5fa] transition-colors">
                                    {{ $disc->game->title ?? '—' }}
                                </p>
                                <p class="text-[10px] text-gray-600">{{ $disc->game->publisher->name ?? '—' }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Persen --}}
                    <td class="px-3 py-3 text-center">
                        <span class="inline-block text-base font-black text-orange-400 bg-orange-400/10 px-3 py-1 rounded-xl">
                            -{{ number_format($disc->discount_pct, 0) }}%
                        </span>
                    </td>

                    {{-- Harga asli --}}
                    <td class="px-3 py-3 hidden sm:table-cell">
                        @if($disc->game)
                        <div>
                            <p class="text-xs text-gray-500 line-through">Rp {{ number_format($disc->game->base_price, 0, ',', '.') }}</p>
                            <p class="text-xs font-semibold text-emerald-400">
                                Rp {{ number_format($disc->game->base_price * (1 - $disc->discount_pct/100), 0, ',', '.') }}
                            </p>
                        </div>
                        @else
                        <span class="text-gray-600 text-xs">—</span>
                        @endif
                    </td>

                    {{-- Tanggal mulai --}}
                    <td class="px-3 py-3 hidden md:table-cell">
                        <p class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($disc->start_date)->format('d M Y') }}</p>
                        <p class="text-[10px] text-gray-600">{{ \Carbon\Carbon::parse($disc->start_date)->format('H:i') }}</p>
                    </td>

                    {{-- Tanggal berakhir --}}
                    <td class="px-3 py-3 hidden md:table-cell">
                        <p class="text-xs {{ $isExpired ? 'text-red-400' : 'text-gray-400' }}">
                            {{ \Carbon\Carbon::parse($disc->end_date)->format('d M Y') }}
                        </p>
                        <p class="text-[10px] text-gray-600">
                            {{ $isExpired ? 'Kadaluarsa ' : '' }}{{ \Carbon\Carbon::parse($disc->end_date)->diffForHumans() }}
                        </p>
                    </td>

                    {{-- Status --}}
                    <td class="px-3 py-3">
                        @if($isRunning)
                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-1 rounded-full bg-emerald-500/10 text-emerald-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>Aktif
                        </span>
                        @elseif($isUpcoming)
                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-1 rounded-full bg-blue-500/10 text-blue-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>Akan Datang
                        </span>
                        @elseif($isExpired)
                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-1 rounded-full bg-gray-500/10 text-gray-500">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span>Kadaluarsa
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-1 rounded-full bg-red-500/10 text-red-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>Nonaktif
                        </span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.discounts.edit', $disc->discount_id) }}"
                               title="Edit"
                               class="p-1.5 rounded-lg text-gray-500 hover:text-[#60a5fa] hover:bg-[#0078f2]/10 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.discounts.destroy', $disc->discount_id) }}"
                                  onsubmit="return confirm('Hapus diskon ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" title="Hapus"
                                        class="p-1.5 rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-16 text-gray-600">
                        <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <p class="text-sm">Tidak ada diskon ditemukan.</p>
                        <a href="{{ route('admin.discounts.create') }}" class="mt-2 inline-block text-xs text-[#60a5fa] hover:underline">+ Buat diskon baru</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($discounts->hasPages())
    <div class="border-t border-[#1e1e22] px-4 py-3 flex items-center justify-between gap-4 flex-wrap">
        <p class="text-xs text-gray-500">
            Menampilkan <span class="text-white">{{ $discounts->firstItem() }}–{{ $discounts->lastItem() }}</span>
            dari <span class="text-white">{{ $discounts->total() }}</span>
        </p>
        <div class="flex items-center gap-1">
            @if($discounts->onFirstPage())
                <span class="px-btn text-gray-600 cursor-not-allowed">← Prev</span>
            @else
                <a href="{{ $discounts->previousPageUrl() }}" class="px-btn text-gray-400 hover:text-white hover:bg-[#1e1e22]">← Prev</a>
            @endif
            @foreach($discounts->getUrlRange(max(1,$discounts->currentPage()-2),min($discounts->lastPage(),$discounts->currentPage()+2)) as $p=>$u)
            <a href="{{ $u }}" class="px-btn {{ $p===$discounts->currentPage()?'bg-[#0078f2] text-white font-semibold':'text-gray-400 hover:text-white hover:bg-[#1e1e22]' }}">{{ $p }}</a>
            @endforeach
            @if($discounts->hasMorePages())
                <a href="{{ $discounts->nextPageUrl() }}" class="px-btn text-gray-400 hover:text-white hover:bg-[#1e1e22]">Next →</a>
            @else
                <span class="px-btn text-gray-600 cursor-not-allowed">Next →</span>
            @endif
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
    .adm-input { @apply w-full bg-[#0d0d0f] border border-[#2a2a30] rounded-xl px-3.5 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-[#0078f2] focus:ring-1 focus:ring-[#0078f2]/30 transition-all; }
    .tbl-th { @apply text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-4 py-3; }
    .px-btn { @apply px-3 py-1.5 rounded-lg text-xs bg-[#0d0d0f] transition-all; }
    select.adm-input option { background-color: #111114; }
</style>
@endpush
@endsection
