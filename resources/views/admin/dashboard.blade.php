@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('breadcrumb', 'Dashboard')

@section('content')

{{-- ============================================================ --}}
{{-- HEADER                                                        --}}
{{-- ============================================================ --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-white">Selamat datang, {{ auth()->user()->username }} 👋</h1>
    <p class="text-gray-400 text-sm mt-1">Berikut ringkasan aktivitas Epic Games Store hari ini.</p>
</div>

{{-- ============================================================ --}}
{{-- STAT CARDS                                                    --}}
{{-- ============================================================ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">

    {{-- Total Revenue --}}
    <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-5 flex flex-col gap-3 hover:border-[#0078f2]/40 transition-colors group">
        <div class="flex items-start justify-between">
            <div class="p-2.5 rounded-xl bg-[#0078f2]/10 text-[#4da3ff] group-hover:bg-[#0078f2]/20 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="text-xs text-green-400 bg-green-400/10 px-2 py-1 rounded-full font-medium">Revenue</span>
        </div>
        <div>
            <p class="text-2xl font-bold text-white">
                Rp {{ number_format($stats['totalRevenue'], 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-500 mt-0.5">Total pendapatan selesai</p>
        </div>
    </div>

    {{-- Total Transactions --}}
    <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-5 flex flex-col gap-3 hover:border-purple-500/40 transition-colors group">
        <div class="flex items-start justify-between">
            <div class="p-2.5 rounded-xl bg-purple-500/10 text-purple-400 group-hover:bg-purple-500/20 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <span class="text-xs text-purple-400 bg-purple-400/10 px-2 py-1 rounded-full font-medium">Transaksi</span>
        </div>
        <div>
            <p class="text-2xl font-bold text-white">{{ number_format($stats['totalTransactions']) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Transaksi berhasil</p>
        </div>
    </div>

    {{-- Total Users --}}
    <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-5 flex flex-col gap-3 hover:border-cyan-500/40 transition-colors group">
        <div class="flex items-start justify-between">
            <div class="p-2.5 rounded-xl bg-cyan-500/10 text-cyan-400 group-hover:bg-cyan-500/20 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <span class="text-xs text-cyan-400 bg-cyan-400/10 px-2 py-1 rounded-full font-medium">Pengguna</span>
        </div>
        <div>
            <p class="text-2xl font-bold text-white">{{ number_format($stats['totalUsers']) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Pengguna aktif</p>
        </div>
    </div>

    {{-- Total Games --}}
    <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-5 flex flex-col gap-3 hover:border-orange-500/40 transition-colors group">
        <div class="flex items-start justify-between">
            <div class="p-2.5 rounded-xl bg-orange-500/10 text-orange-400 group-hover:bg-orange-500/20 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"/>
                </svg>
            </div>
            <span class="text-xs text-orange-400 bg-orange-400/10 px-2 py-1 rounded-full font-medium">Game</span>
        </div>
        <div>
            <p class="text-2xl font-bold text-white">{{ number_format($stats['totalGames']) }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Game aktif di store</p>
        </div>
    </div>

</div>

{{-- ============================================================ --}}
{{-- CHART + TOP GAMES                                             --}}
{{-- ============================================================ --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-8">

    {{-- Revenue Chart --}}
    <div class="xl:col-span-2 bg-[#111114] border border-[#1e1e22] rounded-2xl p-5">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-sm font-semibold text-white">Pendapatan Bulanan</h2>
                <p class="text-xs text-gray-500 mt-0.5">12 bulan terakhir</p>
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <span class="w-2.5 h-2.5 rounded-full bg-[#0078f2] inline-block"></span>
                Revenue (Rp)
            </div>
        </div>
        <div class="relative h-56">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-5">
        <h2 class="text-sm font-semibold text-white mb-4">Aksi Cepat</h2>
        <div class="space-y-2">
            <a href="{{ route('admin.games.create') }}"
               class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 border border-transparent hover:border-[#0078f2]/30 transition-all group">
                <div class="p-2 rounded-lg bg-[#0078f2]/10 text-[#4da3ff] group-hover:bg-[#0078f2]/20 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-white">Tambah Game</p>
                    <p class="text-xs text-gray-500">Input game baru ke store</p>
                </div>
            </a>
            <a href="{{ route('admin.news.create') }}"
               class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 border border-transparent hover:border-purple-500/30 transition-all group">
                <div class="p-2 rounded-lg bg-purple-500/10 text-purple-400 group-hover:bg-purple-500/20 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-white">Tulis Berita</p>
                    <p class="text-xs text-gray-500">Publikasikan artikel baru</p>
                </div>
            </a>
            <a href="{{ route('admin.discounts.create') }}"
               class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 border border-transparent hover:border-green-500/30 transition-all group">
                <div class="p-2 rounded-lg bg-green-500/10 text-green-400 group-hover:bg-green-500/20 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-white">Buat Diskon</p>
                    <p class="text-xs text-gray-500">Tambahkan promo baru</p>
                </div>
            </a>
            <a href="{{ route('admin.platforms.create') }}"
               class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 border border-transparent hover:border-orange-500/30 transition-all group">
                <div class="p-2 rounded-lg bg-orange-500/10 text-orange-400 group-hover:bg-orange-500/20 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-white">Tambah Platform</p>
                    <p class="text-xs text-gray-500">Daftarkan platform baru</p>
                </div>
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 border border-transparent hover:border-cyan-500/30 transition-all group">
                <div class="p-2 rounded-lg bg-cyan-500/10 text-cyan-400 group-hover:bg-cyan-500/20 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-white">Kelola Pengguna</p>
                    <p class="text-xs text-gray-500">Lihat & atur akun user</p>
                </div>
            </a>
        </div>
    </div>

</div>

{{-- ============================================================ --}}
{{-- TOP GAMES + RECENT TRANSACTIONS                               --}}
{{-- ============================================================ --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-4">

    {{-- Top Selling Games --}}
    <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-white">Game Terlaris</h2>
            <a href="{{ route('admin.games.index') }}"
               class="text-xs text-[#4da3ff] hover:underline">Lihat semua →</a>
        </div>

        @if($topGames->isEmpty())
        <p class="text-gray-500 text-sm text-center py-8">Belum ada data penjualan.</p>
        @else
        <div class="space-y-3">
            @foreach($topGames as $index => $game)
            <div class="flex items-center gap-3 p-2 rounded-xl hover:bg-white/5 transition-colors">
                {{-- Rank --}}
                <span class="text-xs font-bold w-5 text-center shrink-0 {{ $index === 0 ? 'text-yellow-400' : ($index === 1 ? 'text-gray-300' : ($index === 2 ? 'text-orange-400' : 'text-gray-600')) }}">
                    {{ $index + 1 }}
                </span>
                {{-- Cover --}}
                <img src="{{ $game->cover_image_url ?? 'https://placehold.co/40x56/18181c/fff?text=?' }}"
                     alt="{{ $game->title }}"
                     class="w-8 h-11 object-cover rounded-md shrink-0 ring-1 ring-white/10"
                     onerror="this.src='https://placehold.co/40x56/18181c/fff?text=?'">
                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ $game->title }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $game->publisher ?? '—' }}</p>
                </div>
                {{-- Stats --}}
                <div class="text-right shrink-0">
                    <p class="text-xs font-semibold text-white">{{ number_format($game->total_sold) }} terjual</p>
                    <p class="text-xs text-gray-500">Rp {{ number_format($game->total_revenue ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Recent Transactions --}}
    <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-white">Transaksi Terbaru</h2>
        </div>

        @if($recentTransactions->isEmpty())
        <p class="text-gray-500 text-sm text-center py-8">Belum ada transaksi.</p>
        @else
        <div class="space-y-2">
            @foreach($recentTransactions as $trx)
            <div class="flex items-center gap-3 p-3 rounded-xl bg-[#0d0d0f] border border-[#1e1e22] hover:border-[#2a2a30] transition-colors">
                {{-- Avatar --}}
                <div class="w-8 h-8 rounded-full bg-[#0078f2]/20 flex items-center justify-center text-xs font-bold text-[#4da3ff] shrink-0">
                    {{ strtoupper(substr($trx->user->username ?? 'U', 0, 1)) }}
                </div>
                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ $trx->user->username ?? '—' }}</p>
                    <p class="text-xs text-gray-500">
                        {{ $trx->completed_at ? \Carbon\Carbon::parse($trx->completed_at)->format('d M Y H:i') : '—' }}
                    </p>
                </div>
                {{-- Amount --}}
                <div class="text-right shrink-0">
                    <p class="text-sm font-semibold text-green-400">
                        Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                    </p>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-green-500/10 text-green-400">Selesai</span>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const labels = @json($chartLabels);
    const data   = @json($chartData);

    if (!labels.length) return;

    const ctx = document.getElementById('revenueChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data,
                borderColor: '#0078f2',
                backgroundColor: 'rgba(0,120,242,0.08)',
                borderWidth: 2,
                pointBackgroundColor: '#0078f2',
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
                    callbacks: {
                        label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID'),
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: '#1e1e22' },
                    ticks: { color: '#6b7280', font: { size: 11 } },
                },
                y: {
                    grid: { color: '#1e1e22' },
                    ticks: {
                        color: '#6b7280',
                        font: { size: 11 },
                        callback: v => 'Rp ' + (v / 1e6).toFixed(1) + 'Jt',
                    }
                }
            }
        }
    });
});
</script>
@endpush
