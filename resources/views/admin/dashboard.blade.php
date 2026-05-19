@extends('layouts.admin')
@section('title','Dashboard Admin')
@section('breadcrumb','Dashboard')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-white">Dashboard Admin</h1>
        <p class="text-gray-500 text-xs mt-0.5">Selamat datang, <span class="text-[#60a5fa]">{{ auth()->user()->username }}</span></p>
    </div>
    <a href="{{ route('admin.games.create') }}"
       class="flex items-center gap-2 bg-[#0078f2] hover:bg-[#0063cc] text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all shadow-lg shadow-[#0078f2]/20">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Game
    </a>
</div>

{{-- ============================================================ --}}
{{-- STAT CARDS ROW 1 — Game                                      --}}
{{-- ============================================================ --}}
<p class="text-[11px] font-semibold text-gray-600 uppercase tracking-widest mb-2">Statistik Game</p>
<div class="grid grid-cols-2 xl:grid-cols-4 gap-3 mb-5">

    {{-- Total Game Aktif --}}
    <a href="{{ route('admin.games.index') }}"
       class="stat-card border-[#1e1e22] hover:border-[#0078f2]/50 group">
        <div class="stat-icon bg-[#0078f2]/10 text-[#60a5fa]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-white mt-3">{{ number_format($stats['totalGames']) }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Game Aktif</p>
        <p class="stat-sub">{{ $stats['totalBaseGames'] }} base · {{ $stats['totalAddon'] }} addon</p>
    </a>

    {{-- Game Nonaktif --}}
    <a href="{{ route('admin.games.index') }}?status=inactive"
       class="stat-card border-[#1e1e22] hover:border-red-500/40 group">
        <div class="stat-icon bg-red-500/10 text-red-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-white mt-3">{{ number_format($stats['totalInactiveGames']) }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Game Dinonaktifkan</p>
        <p class="stat-sub">{{ $stats['totalFreeGames'] }} game gratis aktif</p>
    </a>

    {{-- Avg Rating --}}
    <div class="stat-card border-[#1e1e22] hover:border-yellow-500/40 group">
        <div class="stat-icon bg-yellow-500/10 text-yellow-400">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-white mt-3">{{ number_format($stats['avgRating'],2) }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Avg Rating</p>
        <p class="stat-sub">Rata-rata semua game aktif</p>
    </div>

    {{-- Diskon Aktif --}}
    <a href="{{ route('admin.discounts.index') }}"
       class="stat-card border-[#1e1e22] hover:border-orange-500/40 group">
        <div class="stat-icon bg-orange-500/10 text-orange-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-white mt-3">{{ number_format($stats['totalActiveDiscounts']) }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Diskon Aktif</p>
        <p class="stat-sub">Promo berjalan sekarang</p>
    </a>

</div>

{{-- ============================================================ --}}
{{-- STAT CARDS ROW 2 — User & Transaksi                          --}}
{{-- ============================================================ --}}
<p class="text-[11px] font-semibold text-gray-600 uppercase tracking-widest mb-2">Statistik Pengguna & Transaksi</p>
<div class="grid grid-cols-2 xl:grid-cols-4 gap-3 mb-6">

    {{-- Total User --}}
    <a href="{{ route('admin.users.index') }}"
       class="stat-card border-[#1e1e22] hover:border-cyan-500/40 group">
        <div class="stat-icon bg-cyan-500/10 text-cyan-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-white mt-3">{{ number_format($stats['totalUsers']) }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Total Pengguna</p>
        <p class="stat-sub">{{ $stats['totalNewUsers'] }} baru (30 hari terakhir)</p>
    </a>

    {{-- User Bertransaksi --}}
    <a href="{{ route('admin.users.index') }}"
       class="stat-card border-[#1e1e22] hover:border-violet-500/40 group">
        <div class="stat-icon bg-violet-500/10 text-violet-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-white mt-3">{{ number_format($stats['totalUsersWithTrx']) }}</p>
        <p class="text-xs text-gray-500 mt-0.5">User Bertransaksi</p>
        <p class="stat-sub">{{ $stats['totalInactiveUsers'] }} akun dinonaktifkan</p>
    </a>

    {{-- Total Transaksi --}}
    <div class="stat-card border-[#1e1e22] hover:border-emerald-500/40 group">
        <div class="stat-icon bg-emerald-500/10 text-emerald-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-white mt-3">{{ number_format($stats['totalTransactions']) }}</p>
        <p class="text-xs text-gray-500 mt-0.5">Total Transaksi</p>
        <p class="stat-sub">{{ $stats['totalTransactionsThisMonth'] }} bulan ini</p>
    </div>

    {{-- Revenue --}}
    <div class="stat-card border-[#1e1e22] hover:border-[#0078f2]/40 group">
        <div class="stat-icon bg-[#0078f2]/10 text-[#60a5fa]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <p class="text-2xl font-bold text-white mt-3">
            Rp {{ number_format($stats['totalRevenue']/1e6,1,',','.') }}Jt
        </p>
        <p class="text-xs text-gray-500 mt-0.5">Total Revenue</p>
        <p class="stat-sub">Rp {{ number_format($stats['revenueThisMonth'],0,',','.') }} bulan ini</p>
    </div>

</div>

{{-- ============================================================ --}}
{{-- CHART + GAME TYPE                                             --}}
{{-- ============================================================ --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-5">

    <div class="xl:col-span-2 panel">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="panel-title">Pendapatan Bulanan</h2>
                <p class="panel-sub">12 bulan terakhir</p>
            </div>
            <span class="badge-blue"><span class="w-2 h-2 rounded-full bg-[#0078f2]"></span> Revenue</span>
        </div>
        <div class="h-52"><canvas id="revenueChart"></canvas></div>
    </div>

    <div class="panel">
        <h2 class="panel-title mb-1">Distribusi Tipe Game</h2>
        <p class="panel-sub mb-4">Semua game aktif</p>
        <div class="h-36 flex items-center justify-center mb-3">
            <canvas id="typeChart"></canvas>
        </div>
        <div class="space-y-2">
            @foreach($gameTypeStats as $type)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full shrink-0" style="background:{{ $type['color'] }}"></span>
                    <span class="text-[11px] text-gray-400 capitalize">{{ str_replace('_',' ',$type['type']) }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-16 h-1.5 bg-[#1e1e22] rounded-full overflow-hidden">
                        <div class="h-full rounded-full" style="width:{{ $type['pct'] }}%;background:{{ $type['color'] }}"></div>
                    </div>
                    <span class="text-[11px] font-bold text-white w-6 text-right">{{ $type['count'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

{{-- ============================================================ --}}
{{-- TOP 5 TERLARIS + HIGH SPENDERS                               --}}
{{-- ============================================================ --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-5">

    {{-- Top 5 Game Terlaris --}}
    <div class="panel">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="panel-title">Top 5 Game Terlaris</h2>
                <p class="panel-sub">Berdasarkan jumlah transaksi selesai</p>
            </div>
            <a href="{{ route('admin.games.index') }}" class="text-[11px] text-[#60a5fa] hover:underline">Kelola →</a>
        </div>
        @forelse($topGames as $i => $game)
        @php $medals=['text-yellow-400 bg-yellow-400/10','text-gray-300 bg-gray-500/10','text-orange-400 bg-orange-400/10']; @endphp
        <div class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-white/[0.03] transition-colors group">
            <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-black shrink-0 {{ $medals[$i] ?? 'text-gray-600 bg-gray-700/10' }}">
                {{ $i+1 }}
            </span>
            <img src="{{ $game->cover_image_url ?? 'https://placehold.co/40x56/18181c/fff?text=?' }}"
                 alt="{{ $game->title }}"
                 class="w-8 h-11 object-cover rounded-md shrink-0 ring-1 ring-white/10"
                 onerror="this.src='https://placehold.co/40x56/18181c/fff?text=?'">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate group-hover:text-[#60a5fa] transition-colors">{{ $game->title }}</p>
                <p class="text-[11px] text-gray-500 truncate">{{ $game->publisher ?? '—' }}</p>
            </div>
            <div class="text-right shrink-0">
                <p class="text-xs font-bold text-white">{{ number_format($game->total_sold) }} <span class="text-gray-500 font-normal">terjual</span></p>
                <p class="text-[11px] text-emerald-400">Rp {{ number_format($game->total_revenue??0,0,',','.') }}</p>
            </div>
        </div>
        @empty
        <p class="text-gray-600 text-xs text-center py-8">Belum ada data penjualan.</p>
        @endforelse
    </div>

    {{-- High Spenders --}}
    <div class="panel">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="panel-title">Top 5 Pengguna High Spender</h2>
                <p class="panel-sub">User dengan total pengeluaran terbesar</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="text-[11px] text-[#60a5fa] hover:underline">Lihat →</a>
        </div>
        @forelse($highSpenders as $i => $user)
        <div class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-white/[0.03] transition-colors">
            <span class="w-7 h-7 rounded-lg flex items-center justify-center text-xs font-black shrink-0
                {{ ['text-yellow-400 bg-yellow-400/10','text-gray-300 bg-gray-500/10','text-orange-400 bg-orange-400/10'][$i] ?? 'text-gray-600 bg-gray-700/10' }}">
                {{ $i+1 }}
            </span>
            <div class="w-8 h-8 rounded-full bg-[#0078f2]/20 flex items-center justify-center text-xs font-bold text-[#60a5fa] shrink-0">
                {{ strtoupper(substr($user->username??'U',0,1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ $user->username }}</p>
                <p class="text-[11px] text-gray-500 truncate">{{ $user->total_transactions }} transaksi</p>
            </div>
            <div class="text-right shrink-0">
                <p class="text-xs font-bold text-emerald-400">Rp {{ number_format($user->total_spent,0,',','.') }}</p>
                <p class="text-[10px] text-gray-600">total dibelanjakan</p>
            </div>
        </div>
        @empty
        <p class="text-gray-600 text-xs text-center py-8">Belum ada data transaksi.</p>
        @endforelse
    </div>

</div>

{{-- ============================================================ --}}
{{-- GENRE POPULER + DISKON AKTIF                                 --}}
{{-- ============================================================ --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-5">

    {{-- Genre Paling Populer --}}
    <div class="panel">
        <div class="mb-4">
            <h2 class="panel-title">Genre Paling Populer</h2>
            <p class="panel-sub">Berdasarkan total unit terjual per genre</p>
        </div>
        @if($popularGenres->isEmpty())
        <p class="text-gray-600 text-xs text-center py-8">Belum ada data.</p>
        @else
        @php $maxSold = $popularGenres->max('total_sold') ?: 1; @endphp
        <div class="space-y-3">
            @foreach($popularGenres as $genre)
            @php $pct = round($genre->total_sold / $maxSold * 100); @endphp
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium text-white">{{ $genre->genre_name }}</span>
                    <div class="text-right">
                        <span class="text-xs font-bold text-white">{{ number_format($genre->total_sold) }}</span>
                        <span class="text-[10px] text-gray-500 ml-1">terjual</span>
                    </div>
                </div>
                <div class="w-full h-2 bg-[#1e1e22] rounded-full overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-[#0078f2] to-[#60a5fa] transition-all duration-700"
                         style="width:{{ $pct }}%"></div>
                </div>
                <p class="text-[10px] text-gray-600 mt-0.5">{{ $genre->total_games }} game · Rp {{ number_format($genre->total_revenue,0,',','.') }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Diskon Aktif --}}
    <div class="panel">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="panel-title">Diskon Aktif</h2>
                <p class="panel-sub">Promo yang sedang berjalan sekarang</p>
            </div>
            <a href="{{ route('admin.discounts.index') }}" class="text-[11px] text-[#60a5fa] hover:underline">Kelola →</a>
        </div>
        @forelse($activeDiscounts as $disc)
        <div class="flex items-center gap-3 p-2.5 rounded-xl bg-[#0d0d0f] border border-[#1e1e22] hover:border-[#2a2a30] transition-colors mb-2">
            <div class="shrink-0 w-12 h-12 rounded-xl bg-[#0078f2]/10 flex flex-col items-center justify-center">
                <span class="text-base font-black text-[#60a5fa] leading-none">{{ $disc->discount_pct }}%</span>
                <span class="text-[9px] text-gray-500 leading-none mt-0.5">OFF</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ $disc->game->title ?? '—' }}</p>
                <p class="text-[11px] text-gray-500">
                    s/d {{ \Carbon\Carbon::parse($disc->end_date)->format('d M Y') }}
                    · {{ \Carbon\Carbon::parse($disc->end_date)->diffForHumans() }}
                </p>
            </div>
            <span class="text-[10px] font-semibold px-2 py-1 rounded-full bg-emerald-500/10 text-emerald-400 shrink-0">Aktif</span>
        </div>
        @empty
        <div class="flex flex-col items-center justify-center py-8 text-gray-600">
            <svg class="w-9 h-9 mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <p class="text-xs">Tidak ada diskon aktif saat ini</p>
            <a href="{{ route('admin.discounts.create') }}" class="mt-2 text-xs text-[#60a5fa] hover:underline">+ Buat diskon baru</a>
        </div>
        @endforelse
    </div>

</div>

{{-- ============================================================ --}}
{{-- GAME TERBARU (tabel)                                          --}}
{{-- ============================================================ --}}
<div class="panel">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="panel-title">Game Terbaru Ditambahkan</h2>
            <p class="panel-sub">5 game terakhir di store</p>
        </div>
        <a href="{{ route('admin.games.index') }}" class="text-[11px] text-[#60a5fa] hover:underline">Lihat semua →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-[11px] text-gray-500 border-b border-[#1e1e22]">
                    <th class="text-left font-medium pb-2.5 pr-3">Game</th>
                    <th class="text-left font-medium pb-2.5 pr-3 hidden md:table-cell">Tipe</th>
                    <th class="text-left font-medium pb-2.5 pr-3 hidden sm:table-cell">Harga</th>
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
                            <p class="text-white text-xs font-medium truncate max-w-[180px] group-hover:text-[#60a5fa] transition-colors">
                                {{ $game->title }}
                            </p>
                        </div>
                    </td>
                    <td class="py-2.5 pr-3 hidden md:table-cell">
                        <span class="text-[10px] px-2 py-0.5 rounded-full bg-[#1e1e22] text-gray-400 whitespace-nowrap">
                            {{ ucfirst(str_replace('_',' ',$game->game_type)) }}
                        </span>
                    </td>
                    <td class="py-2.5 pr-3 hidden sm:table-cell">
                        @if($game->base_price == 0)
                            <span class="text-emerald-400 text-xs font-bold">GRATIS</span>
                        @else
                            <span class="text-white text-xs">Rp {{ number_format($game->base_price,0,',','.') }}</span>
                        @endif
                    </td>
                    <td class="py-2.5">
                        @if($game->is_active)
                            <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-red-500/10 text-red-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>Nonaktif
                            </span>
                        @endif
                    </td>
                    <td class="py-2.5 text-right">
                        <a href="{{ route('admin.games.edit', $game->game_id) }}"
                           class="p-1.5 rounded-lg text-gray-500 hover:text-[#60a5fa] hover:bg-[#0078f2]/10 transition-all inline-flex">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Revenue chart
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
                        const g = ctx.chart.ctx.createLinearGradient(0,0,0,200);
                        g.addColorStop(0,'rgba(0,120,242,0.15)');
                        g.addColorStop(1,'rgba(0,120,242,0)');
                        return g;
                    },
                    borderWidth: 2, pointBackgroundColor: '#0078f2',
                    pointBorderColor: '#111114', pointBorderWidth: 2,
                    pointRadius: 3, pointHoverRadius: 5, fill: true, tension: 0.4,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false },
                    tooltip: { backgroundColor:'#18181c', borderColor:'#2a2a30', borderWidth:1,
                        titleColor:'#fff', bodyColor:'#9ca3af', padding:10,
                        callbacks: { label: ctx => ' Rp '+Number(ctx.parsed.y).toLocaleString('id-ID') }
                    }
                },
                scales: {
                    x: { grid:{color:'#1a1a1e'}, ticks:{color:'#4b5563',font:{size:10}} },
                    y: { grid:{color:'#1a1a1e'}, ticks:{color:'#4b5563',font:{size:10},
                        callback: v => v>=1e6?'Rp'+(v/1e6).toFixed(1)+'Jt':'Rp'+(v/1e3).toFixed(0)+'Rb' }}
                }
            }
        });
    }
    // Doughnut chart
    const typeCtx = document.getElementById('typeChart');
    if (typeCtx) {
        const d = @json($gameTypeStats);
        new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: d.map(x=>x.type.replace(/_/g,' ')),
                datasets: [{ data: d.map(x=>x.count), backgroundColor: d.map(x=>x.color),
                    borderColor:'#111114', borderWidth:3, hoverOffset:4 }]
            },
            options: { responsive:true, maintainAspectRatio:false, cutout:'72%',
                plugins: { legend:{display:false},
                    tooltip:{ backgroundColor:'#18181c', borderColor:'#2a2a30', borderWidth:1,
                        titleColor:'#fff', bodyColor:'#9ca3af',
                        callbacks:{label:ctx=>' '+ctx.label+': '+ctx.parsed+' game'} }
                }
            }
        });
    }
});
</script>
@endpush

@push('styles')
<style>
    .stat-card {
        @apply bg-[#111114] border rounded-2xl p-5 flex flex-col transition-all duration-200 cursor-pointer hover:shadow-lg;
        text-decoration: none;
    }
    .stat-icon {
        @apply p-2.5 rounded-xl w-fit;
    }
    .stat-sub {
        @apply text-[11px] text-gray-600 border-t border-[#1e1e22] pt-2.5 mt-3 leading-relaxed;
    }
    .panel {
        @apply bg-[#111114] border border-[#1e1e22] rounded-2xl p-5;
    }
    .panel-title { @apply text-sm font-semibold text-white; }
    .panel-sub   { @apply text-[11px] text-gray-500; }
    .badge-blue  { @apply flex items-center gap-1.5 text-[11px] bg-[#0078f2]/10 text-[#60a5fa] px-3 py-1.5 rounded-full; }
</style>
@endpush
