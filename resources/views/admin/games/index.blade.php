@extends('layouts.admin')

@section('title', 'Kelola Game')
@section('breadcrumb', 'Kelola Game')

@section('content')

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
    <div>
        <h1 class="text-xl font-bold text-white">Kelola Game</h1>
        <p class="text-gray-500 text-xs mt-0.5">Total <span class="text-white font-semibold">{{ $games->total() }}</span> game ditemukan</p>
    </div>
    <a href="{{ route('admin.games.create') }}"
       class="flex items-center gap-2 bg-[#0078f2] hover:bg-[#0063cc] text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all shadow-lg shadow-[#0078f2]/20 whitespace-nowrap self-start sm:self-auto">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Game
    </a>
</div>

{{-- Filter Bar --}}
<div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-4 mb-5">
    <form method="GET" action="{{ route('admin.games.index') }}"
          class="flex flex-col sm:flex-row gap-3">

        {{-- Search --}}
        <div class="flex-1 relative">
            <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="q" value="{{ request('q') }}"
                   placeholder="Cari judul atau deskripsi game..."
                   class="w-full bg-[#0d0d0f] border border-[#2a2a30] rounded-xl pl-10 pr-4 py-2.5 text-sm text-white
                          placeholder-gray-600 focus:outline-none focus:border-[#0078f2] focus:ring-1 focus:ring-[#0078f2]/30 transition-all">
        </div>

        {{-- Filter Status --}}
        <select name="status"
                class="bg-[#0d0d0f] border border-[#2a2a30] rounded-xl px-3 py-2.5 text-sm text-gray-300
                       focus:outline-none focus:border-[#0078f2] transition-all">
            <option value="">Semua Status</option>
            <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
        </select>

        {{-- Filter Tipe --}}
        <select name="type"
                class="bg-[#0d0d0f] border border-[#2a2a30] rounded-xl px-3 py-2.5 text-sm text-gray-300
                       focus:outline-none focus:border-[#0078f2] transition-all">
            <option value="">Semua Tipe</option>
            @foreach(['base_game','edition','addon','aplikasi','editor','langganan','pengalaman','bundle','demo'] as $t)
            <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>
                {{ ucfirst(str_replace('_', ' ', $t)) }}
            </option>
            @endforeach
        </select>

        <button type="submit"
                class="bg-[#0078f2] hover:bg-[#0063cc] text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all whitespace-nowrap">
            Cari
        </button>

        @if(request()->hasAny(['q','status','type']))
        <a href="{{ route('admin.games.index') }}"
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
                    <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Game</th>
                    <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-3 py-3 hidden md:table-cell">Tipe</th>
                    <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-3 py-3 hidden lg:table-cell">Publisher</th>
                    <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-3 py-3 hidden sm:table-cell">Harga</th>
                    <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-3 py-3 hidden xl:table-cell">Rating</th>
                    <th class="text-left text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-3 py-3">Status</th>
                    <th class="text-right text-[11px] font-semibold text-gray-500 uppercase tracking-wider px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#1a1a1e]">
                @forelse($games as $game)
                <tr class="hover:bg-white/[0.02] transition-colors group">

                    {{-- Cover + Judul --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $game->cover_image_url ?? 'https://placehold.co/40x56/18181c/fff?text=?' }}"
                                 alt="{{ $game->title }}"
                                 class="w-9 h-12 object-cover rounded-lg shrink-0 ring-1 ring-white/10"
                                 onerror="this.src='https://placehold.co/40x56/18181c/fff?text=?'">
                            <div class="min-w-0">
                                <p class="font-semibold text-white text-sm truncate max-w-[200px] group-hover:text-[#4da3ff] transition-colors">
                                    {{ $game->title }}
                                </p>
                                @if($game->info)
                                <span class="text-[10px] font-medium text-[#4da3ff] bg-[#0078f2]/10 px-1.5 py-0.5 rounded">
                                    {{ $game->info_label }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Tipe --}}
                    <td class="px-3 py-3 hidden md:table-cell">
                        <span class="text-[11px] font-medium px-2 py-1 rounded-full bg-[#1e1e22] text-gray-400 whitespace-nowrap">
                            {{ ucfirst(str_replace('_', ' ', $game->game_type)) }}
                        </span>
                    </td>

                    {{-- Publisher --}}
                    <td class="px-3 py-3 hidden lg:table-cell">
                        <p class="text-xs text-gray-400 truncate max-w-[120px]">
                            {{ $game->publisher->name ?? '—' }}
                        </p>
                    </td>

                    {{-- Harga --}}
                    <td class="px-3 py-3 hidden sm:table-cell">
                        @if($game->base_price == 0)
                            <span class="text-emerald-400 text-xs font-bold">GRATIS</span>
                        @else
                            <div>
                                <p class="text-xs font-semibold text-white whitespace-nowrap">
                                    Rp {{ number_format($game->base_price, 0, ',', '.') }}
                                </p>
                                @if($game->discount_pct > 0)
                                <span class="text-[10px] font-bold text-orange-400">-{{ $game->discount_pct }}%</span>
                                @endif
                            </div>
                        @endif
                    </td>

                    {{-- Rating --}}
                    <td class="px-3 py-3 hidden xl:table-cell">
                        @if($game->avg_rating)
                        <div class="flex items-center gap-1">
                            <svg class="w-3 h-3 text-yellow-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-xs text-gray-300">{{ number_format($game->avg_rating, 1) }}</span>
                        </div>
                        @else
                        <span class="text-xs text-gray-600">—</span>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td class="px-3 py-3">
                        @if($game->is_active)
                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-1 rounded-full bg-emerald-500/10 text-emerald-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Aktif
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-1 rounded-full bg-red-500/10 text-red-400">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                            Nonaktif
                        </span>
                        @endif
                    </td>

                    {{-- Aksi --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-1">
                            {{-- Lihat --}}
                            <a href="{{ route('game.show', $game->game_id) }}" target="_blank"
                               title="Lihat di Store"
                               class="p-1.5 rounded-lg text-gray-500 hover:text-gray-300 hover:bg-white/10 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            {{-- Edit --}}
                            <a href="{{ route('admin.games.edit', $game->game_id) }}"
                               title="Edit"
                               class="p-1.5 rounded-lg text-gray-500 hover:text-[#4da3ff] hover:bg-[#0078f2]/10 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            {{-- Nonaktifkan / Aktifkan --}}
                            <form method="POST" action="{{ route('admin.games.destroy', $game->game_id) }}"
                                  onsubmit="return confirm('{{ $game->is_active ? 'Nonaktifkan' : 'Hapus permanen' }} game \'{{ addslashes($game->title) }}\'?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        title="{{ $game->is_active ? 'Nonaktifkan' : 'Hapus' }}"
                                        class="p-1.5 rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="{{ $game->is_active
                                                    ? 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636'
                                                    : 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16' }}"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-16 text-gray-600">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
                        </svg>
                        <p class="text-sm">Tidak ada game yang ditemukan.</p>
                        @if(request()->hasAny(['q','status','type']))
                        <a href="{{ route('admin.games.index') }}" class="mt-2 inline-block text-xs text-[#4da3ff] hover:underline">Reset filter</a>
                        @else
                        <a href="{{ route('admin.games.create') }}" class="mt-2 inline-block text-xs text-[#4da3ff] hover:underline">+ Tambah game pertama</a>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($games->hasPages())
    <div class="border-t border-[#1e1e22] px-4 py-3 flex items-center justify-between gap-4">
        <p class="text-xs text-gray-500">
            Menampilkan <span class="text-white">{{ $games->firstItem() }}–{{ $games->lastItem() }}</span>
            dari <span class="text-white">{{ $games->total() }}</span> game
        </p>
        <div class="flex items-center gap-1">
            {{-- Prev --}}
            @if($games->onFirstPage())
            <span class="px-3 py-1.5 rounded-lg text-xs text-gray-600 bg-[#0d0d0f] cursor-not-allowed">← Prev</span>
            @else
            <a href="{{ $games->previousPageUrl() }}"
               class="px-3 py-1.5 rounded-lg text-xs text-gray-400 hover:text-white bg-[#0d0d0f] hover:bg-[#1e1e22] transition-all">← Prev</a>
            @endif

            {{-- Page numbers --}}
            @foreach($games->getUrlRange(max(1, $games->currentPage()-2), min($games->lastPage(), $games->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}"
               class="px-3 py-1.5 rounded-lg text-xs transition-all
                      {{ $page === $games->currentPage()
                            ? 'bg-[#0078f2] text-white font-semibold'
                            : 'text-gray-400 hover:text-white bg-[#0d0d0f] hover:bg-[#1e1e22]' }}">
                {{ $page }}
            </a>
            @endforeach

            {{-- Next --}}
            @if($games->hasMorePages())
            <a href="{{ $games->nextPageUrl() }}"
               class="px-3 py-1.5 rounded-lg text-xs text-gray-400 hover:text-white bg-[#0d0d0f] hover:bg-[#1e1e22] transition-all">Next →</a>
            @else
            <span class="px-3 py-1.5 rounded-lg text-xs text-gray-600 bg-[#0d0d0f] cursor-not-allowed">Next →</span>
            @endif
        </div>
    </div>
    @endif
</div>

@endsection