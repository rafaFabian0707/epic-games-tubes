@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('breadcrumb', 'Dashboard')

@section('content')

{{-- ======================================================= --}}
{{-- HEADER                                                   --}}
{{-- ======================================================= --}}
<div class="flex items-center justify-between mb-7">
    <div>
        <h1 class="text-xl font-bold text-white tracking-tight">Dashboard Admin</h1>
        <p class="text-gray-500 text-xs mt-0.5">
            Selamat datang, <span class="text-[#4da3ff]">{{ auth()->user()->username }}</span>
        </p>
    </div>
    <a href="{{ route('admin.games.create') }}"
       class="flex items-center gap-2 bg-[#0078f2] hover:bg-[#0063cc] text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 shadow-lg shadow-[#0078f2]/20">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Game
    </a>
</div>

{{-- ======================================================= --}}
{{-- STAT CARDS                                               --}}
{{-- ======================================================= --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-3 mb-5">

    <a href="{{ route('admin.games.index') }}"
       class="bg-[#111114] border border-[#1e1e22] hover:border-[#0078f2]/40 rounded-2xl p-5 flex flex-col gap-3 transition-all duration-200 group hover:shadow-lg hover:shadow-[#0078f2]/10">
        <div class="bg-[#0078f2]/10 text-[#4da3ff] p-2.5 rounded-xl w-fit transition-all group-hover:scale-110">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-white tracking-tight">{{ number_format($stats['totalGames']) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total Game Aktif</p>
        </div>
        <p class="text-[11px] text-gray-600 border-t border-[#1e1e22] pt-2.5">
            {{ $stats['totalBaseGames'] }} base game &middot; {{ $stats['totalAddon'] }} addon
        </p>
    </a>

    <a href="{{ route('admin.users.index') }}"
       class="bg-[#111114] border border-[#1e1e22] hover:border-cyan-500/40 rounded-2xl p-5 flex flex-col gap-3 transition-all duration-200 group hover:shadow-lg hover:shadow-cyan-500/10">
        <div class="bg-cyan-500/10 text-cyan-400 p-2.5 rounded-xl w-fit transition-all group-hover:scale-110">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-white tracking-tight">{{ number_format($stats['totalUsers']) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total Pengguna</p>
        </div>
        <p class="text-[11px] text-gray-600 border-t border-[#1e1e22] pt-2.5">
            {{ $stats['totalNewUsers'] }} baru (30 hari terakhir)
        </p>
    </a>

    <div class="bg-[#111114] border border-[#1e1e22] hover:border-violet-500/40 rounded-2xl p-5 flex flex-col gap-3 transition-all duration-200 group hover:shadow-lg hover:shadow-violet-500/10">
        <div class="bg-violet-500/10 text-violet-400 p-2.5 rounded-xl w-fit transition-all group-hover:scale-110">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-white tracking-tight">{{ number_format($stats['totalTransactions']) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Total Transaksi</p>
        </div>
        <p class="text-[11px] text-gray-600 border-t border-[#1e1e22] pt-2.5">
            {{ $stats['totalTransactionsThisMonth'] }} transaksi bulan ini
        </p>
    </div>

    <div class="bg-[#111114] border border-[#1e1e22] hover:border-emerald-500/40 rounded-2xl p-5 flex flex-col gap-3 transition-all duration-200 group hover:shadow-lg hover:shadow-emerald-500/10">
        <div class="bg-emerald-500/10 text-emerald-400 p-2.5 rounded-xl w-fit transition-all group-hover:scale-110">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-white tracking-tight">
                Rp {{ number_format($stats['totalRevenue'] / 1e6, 1, ',', '.') }}Jt
            </p>
            <p class="text-xs text-gray-500 mt-0.5">Total Revenue</p>
        </div>
        <p class="text-[11px] text-gray-600 border-t border-[#1e1e22] pt-2.5">
            Rp {{ number_format($stats['revenueThisMonth'], 0, ',', '.') }} bulan ini
        </p>
    </div>

</div>

{{-- ======================================================= --}}
{{-- CHART + GAME TYPE PIE                                    --}}
{{-- ======================================================= --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-5">

    <div class="xl:col-span-2 bg-[#111114] border border-[#1e1e22] rounded-2xl p-5">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-sm font-semibold text-white">Pendapatan Bulanan</h2>
                <p class="text-[11px] text-gray-500 mt-0.5">12 bulan terakhir (transaksi selesai)</p>
            </div>
            <span class="flex items-center gap-1.5 text-[11px] bg-[#0078f2]/10 text-[#4da3ff] px-3 py-1.5 rounded-full">
                <span class="w-2 h-2 rounded-full bg-[#0078f2]"></span> Revenue
            </span>
        </div>
        <div class="h-52">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-5">
        <div class="mb-4">
            <h2 class="text-sm font-semibold text-white">Distribusi Tipe Game</h2>
            <p class="text-[11px] text-gray-500 mt-0.5">Semua game aktif</p>
        </div>
        <div class="h-36 flex items-center justify-center mb-3">
            <canvas id="typeChart"></canvas>
        </div>
        <div class="space-y-2">
            @foreach($gameTypeStats as $type)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full shrink-0" style="background: {{ $type['color'] }}"></span>
                    <span class="text-[11px] text-gray-400 capitalize">{{ str_replace('_', ' ', $type['type']) }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-16 h-1.5 bg-[#1e1e22] rounded-full overflow-hidden">
                        <div class="h-full rounded-full" style="width: {{ $type['pct'] }}%; background: {{ $type['color'] }}"></div>
                    </div>
                    <span class="text-[11px] font-bold text-white w-6 text-right">{{ $type['count'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- ======================================================= --}}
{{-- TOP 5 TERLARIS + DISKON AKTIF                           --}}
{{-- ======================================================= --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-5">

    {{-- Top 5 Terlaris --}}
    <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-sm font-semibold text-white">Top 5 Game Terlaris</h2>
                <p class="text-[11px] text-gray-500 mt-0.5">Dari view <code class="text-[#4da3ff] bg-[#0078f2]/10 px-1 rounded">vw_top_selling_games</code></p>
            </div>
            <a href="{{ route('admin.games.index') }}" class="text-[11px] text-[#4da3ff] hover:underline">Kelola →</a>
        </div>
        @if($topGames->isEmpty())
        <p class="text-gray-600 text-xs text-center py-8">Belum ada data penjualan.</p>
        @else
        <div class="space-y-2.5">
            @foreach($topGames->take(5) as $i => $game)
            @php
                $medals = [
                    'text-yellow-400 bg-yellow-400/10',
                    'text-gray-300 bg-gray-500/10',
                    'text-orange-400 bg-orange-400/10',
                ];
                $mc = $medals[$i] ?? 'text-gray-600 bg-gray-700/10';
            @endphp
            <div class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-white/[0.03] transition-colors group">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-black shrink-0 {{ $mc }}">
                    {{ $i + 1 }}
                </span>
                <img src="{{ $game->cover_image_url ?? 'https://placehold.co/40x56/18181c/fff?text=?' }}"
                     alt="{{ $game->title }}"
                     class="w-8 h-11 object-cover rounded-md shrink-0 ring-1 ring-white/10"
                     onerror="this.src='https://placehold.co/40x56/18181c/fff?text=?'">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate group-hover:text-[#4da3ff] transition-colors">{{ $game->title }}</p>
                    <p class="text-[11px] text-gray-500 truncate">{{ $game->publisher ?? '—' }}</p>
                </div>
                <div class="text-right shrink-0">
                    <p class="text-xs font-bold text-white">{{ number_format($game->total_sold) }} <span class="text-gray-500 font-normal">terjual</span></p>
                    <p class="text-[11px] text-emerald-400 font-medium">Rp {{ number_format($game->total_revenue ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Diskon Aktif --}}
    <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-sm font-semibold text-white">Diskon Aktif</h2>
                <p class="text-[11px] text-gray-500 mt-0.5">Promo yang sedang berjalan sekarang</p>
            </div>
            <a href="{{ route('admin.discounts.index') }}" class="text-[11px] text-[#4da3ff] hover:underline">Kelola →</a>
        </div>
        @if($activeDiscounts->isEmpty())
        <div class="flex flex-col items-center justify-center py-8 text-gray-600">
            <svg class="w-9 h-9 mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <p class="text-xs">Tidak ada diskon aktif saat ini</p>
            <a href="{{ route('admin.discounts.create') }}" class="mt-2 text-xs text-[#4da3ff] hover:underline">+ Buat diskon baru</a>
        </div>
        @else
        <div class="space-y-2.5">
            @foreach($activeDiscounts as $disc)
            <div class="flex items-center gap-3 p-2.5 rounded-xl bg-[#0d0d0f] border border-[#1e1e22] hover:border-[#2a2a30] transition-colors">
                <div class="shrink-0 w-12 h-12 rounded-xl bg-[#0078f2]/10 flex flex-col items-center justify-center">
                    <span class="text-base font-black text-[#4da3ff] leading-none">{{ $disc->discount_pct }}%</span>
                    <span class="text-[9px] text-gray-500 leading-none mt-0.5">OFF</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ $disc->game->title ?? '—' }}</p>
                    <p class="text-[11px] text-gray-500">
                        s/d {{ \Carbon\Carbon::parse($disc->end_date)->format('d M Y') }}
                    </p>
                </div>
                <div class="text-right shrink-0">
                    <span class="text-[10px] font-semibold px-2 py-1 rounded-full bg-emerald-500/10 text-emerald-400">Aktif</span>
                    <p class="text-[10px] text-gray-600 mt-1">
                        {{ \Carbon\Carbon::parse($disc->end_date)->diffForHumans() }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

{{-- ======================================================= --}}
{{-- GAME TERBARU (tabel ringkas) + INFO SAMPING             --}}
{{-- ======================================================= --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

    <div class="xl:col-span-2 bg-[#111114] border border-[#1e1e22] rounded-2xl p-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-sm font-semibold text-white">Game Terbaru Ditambahkan</h2>
                <p class="text-[11px] text-gray-500 mt-0.5">5 game terakhir di store</p>
            </div>
            <a href="{{ route('admin.games.index') }}" class="text-[11px] text-[#4da3ff] hover:underline">Lihat semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-[11px] text-gray-500 border-b border-[#1e1e22]">
                        <th class="text-left font-medium pb-2.5 pr-3">Game</th>
                        <th class="text-left font-medium pb-2.5 pr-3">Tipe</th>
                        <th class="text-left font-medium pb-2.5 pr-3">Harga</th>
                        <th class="text-left font-medium pb-2.5">Status</th>
                        <th class="text-right font-medium pb-2.5">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#1a1a1e]">
                    @foreach($recentGames as $game)
                    <tr class="hover:bg-white/[0.02] transition-colors group">
                        <td class="py-2.5 pr-3">
                            <div class="flex items-center gap-2.5">
                                <img src="{{ $game->cover_image_url ?? 'https://placehold.co/32x44/18181c/fff?text=?' }}"
                                     alt="{{ $game->title }}"
                                     class="w-7 h-9 object-cover rounded-md shrink-0 ring-1 ring-white/10"
                                     onerror="this.src='https://placehold.co/32x44/18181c/fff?text=?'">
                                <div class="min-w-0">
                                    <p class="text-white font-medium text-xs truncate max-w-[150px] group-hover:text-[#4da3ff] transition-colors">{{ $game->title }}</p>
                                    <p class="text-gray-600 text-[10px] truncate">{{ $game->publisher->name ?? '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-2.5 pr-3">
                            <span class="text-[10px] font-medium px-2 py-0.5 rounded-full bg-[#1e1e22] text-gray-400 whitespace-nowrap">
                                {{ ucfirst(str_replace('_', ' ', $game->game_type)) }}
                            </span>
                        </td>
                        <td class="py-2.5 pr-3">
                            @if($game->base_price == 0)
                                <span class="text-emerald-400 text-xs font-semibold">GRATIS</span>
                            @else
                                <span class="text-white text-xs font-medium">Rp {{ number_format($game->base_price, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td class="py-2.5">
                            @if($game->is_active)
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400">Aktif</span>
                            @else
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-red-500/10 text-red-400">Nonaktif</span>
                            @endif
                        </td>
                        <td class="py-2.5 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.games.edit', $game->game_id) }}"
                                   title="Edit"
                                   class="p-1.5 rounded-lg text-gray-500 hover:text-[#4da3ff] hover:bg-[#0078f2]/10 transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.games.destroy', $game->game_id) }}"
                                      onsubmit="return confirm('Nonaktifkan game \'{{ addslashes($game->title) }}\'?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Nonaktifkan"
                                            class="p-1.5 rounded-lg text-gray-500 hover:text-red-400 hover:bg-red-500/10 transition-all">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Panel info tambahan --}}
    <div class="flex flex-col gap-3">

        <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-4 flex items-center gap-4">
            <div class="p-3 rounded-xl bg-emerald-500/10 text-emerald-400 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white">{{ $stats['totalFreeGames'] }}</p>
                <p class="text-xs text-gray-500">Game Gratis</p>
            </div>
        </div>

        <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-4 flex items-center gap-4">
            <div class="p-3 rounded-xl bg-yellow-500/10 text-yellow-400 shrink-0">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white">{{ number_format($stats['avgRating'], 2) }}</p>
                <p class="text-xs text-gray-500">Avg Rating Game</p>
            </div>
        </div>

        <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-4 flex items-center gap-4">
            <div class="p-3 rounded-xl bg-red-500/10 text-red-400 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white">{{ $stats['totalInactiveGames'] }}</p>
                <p class="text-xs text-gray-500">Game Dinonaktifkan</p>
            </div>
        </div>

        <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-4 flex items-center gap-4">
            <div class="p-3 rounded-xl bg-[#0078f2]/10 text-[#4da3ff] shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-white">{{ $stats['totalActiveDiscounts'] }}</p>
                <p class="text-xs text-gray-500">Diskon Aktif</p>
            </div>
        </div>

        <a href="{{ route('admin.games.index') }}"
           class="bg-[#0078f2]/10 hover:bg-[#0078f2]/20 border border-[#0078f2]/20 hover:border-[#0078f2]/40 rounded-2xl p-4 flex items-center justify-between transition-all group">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-xl bg-[#0078f2]/10 text-[#4da3ff]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-white">Kelola Semua Game</p>
                    <p class="text-[10px] text-gray-500">CRUD lengkap</p>
                </div>
            </div>
            <svg class="w-4 h-4 text-[#4da3ff] group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Revenue Line Chart
    const revCtx = document.getElementById('revenueChart');
    if (revCtx) {
        new Chart(revCtx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    data: @json($chartData),
                    borderColor: '#0078f2',
                    backgroundColor: (ctx) => {
                        const g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 200);
                        g.addColorStop(0, 'rgba(0,120,242,0.15)');
                        g.addColorStop(1, 'rgba(0,120,242,0)');
                        return g;
                    },
                    borderWidth: 2,
                    pointBackgroundColor: '#0078f2',
                    pointBorderColor: '#111114',
                    pointBorderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#18181c',
                        borderColor: '#2a2a30',
                        borderWidth: 1,
                        titleColor: '#fff',
                        bodyColor: '#9ca3af',
                        padding: 10,
                        callbacks: {
                            label: ctx => ' Rp ' + Number(ctx.parsed.y).toLocaleString('id-ID'),
                        }
                    }
                },
                scales: {
                    x: { grid: { color: '#1a1a1e' }, ticks: { color: '#4b5563', font: { size: 10 } } },
                    y: {
                        grid: { color: '#1a1a1e' },
                        ticks: {
                            color: '#4b5563', font: { size: 10 },
                            callback: v => v >= 1e6 ? 'Rp'+(v/1e6).toFixed(1)+'Jt' : 'Rp'+(v/1e3).toFixed(0)+'Rb',
                        }
                    }
                }
            }
        });
    }

    // Doughnut chart tipe game
    const typeCtx = document.getElementById('typeChart');
    if (typeCtx) {
        const typeData = @json($gameTypeStats);
        new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: typeData.map(d => d.type.replace(/_/g, ' ')),
                datasets: [{
                    data: typeData.map(d => d.count),
                    backgroundColor: typeData.map(d => d.color),
                    borderColor: '#111114',
                    borderWidth: 3,
                    hoverOffset: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#18181c',
                        borderColor: '#2a2a30',
                        borderWidth: 1,
                        titleColor: '#fff',
                        bodyColor: '#9ca3af',
                        callbacks: { label: ctx => ' ' + ctx.label + ': ' + ctx.parsed + ' game' }
                    }
                }
            }
        });
    }
});
</script>
@endpush