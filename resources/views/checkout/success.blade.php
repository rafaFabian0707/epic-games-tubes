@extends('layouts.app')

@section('title', 'Pembelian Berhasil — Epic Games')

@section('content')
<div class="min-h-screen bg-gray-950 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ===== SUCCESS HEADER ===== --}}
        <div class="text-center mb-10">
            {{-- Animated check icon --}}
            <div class="w-20 h-20 bg-green-500/10 border-2 border-green-500/40 rounded-full flex items-center justify-center mx-auto mb-5
                        animate-pulse">
                <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-white mb-2">Pembelian Berhasil!</h1>
            <p class="text-gray-400 text-sm">
                Game telah ditambahkan ke library-mu secara otomatis. Selamat bermain!
            </p>
        </div>

        {{-- ===== TRANSACTION CARD ===== --}}
        <div class="bg-gray-900 border border-gray-800 rounded-2xl overflow-hidden mb-6">

            {{-- Header card --}}
            <div class="bg-gray-800/50 px-6 py-4 border-b border-gray-800">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <div>
                        <p class="text-gray-400 text-xs mb-0.5">ID Transaksi</p>
                        <p class="text-white font-mono font-semibold text-sm">
                            #{{ str_pad($transaction->transaction_id, 8, '0', STR_PAD_LEFT) }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-gray-400 text-xs mb-0.5">Tanggal</p>
                        <p class="text-white text-sm">
                            {{ $transaction->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Item list --}}
            <div class="divide-y divide-gray-800">
                @foreach ($transaction->details as $detail)
                    @php $game = $detail->game; @endphp
                    <div class="flex items-center gap-4 px-6 py-4">
                        {{-- Cover --}}
                        <div class="w-16 h-10 rounded-lg overflow-hidden bg-gray-800 flex-shrink-0">
                            @if ($game && $game->cover_image_url)
                                <img src="{{ $game->cover_image_url }}"
                                     alt="{{ $game->title }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        {{-- Info game --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-white text-sm font-medium truncate">
                                {{ $game ? $game->title : 'Game tidak ditemukan' }}
                            </p>
                            @if ($game && $game->publisher)
                                <p class="text-gray-500 text-xs">{{ $game->publisher->name }}</p>
                            @endif
                        </div>

                        {{-- Harga beli --}}
                        <div class="flex-shrink-0 text-right">
                            @if ($detail->discount_applied > 0)
                                <p class="text-xs text-green-400 mb-0.5">-{{ $detail->discount_applied }}%</p>
                            @endif
                            <p class="text-white font-semibold text-sm">
                                @if ($detail->price_at_purchase == 0)
                                    <span class="text-green-400">Gratis</span>
                                @else
                                    ${{ number_format($detail->price_at_purchase, 2) }}
                                @endif
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Footer: total + metode --}}
            <div class="bg-gray-800/30 px-6 py-4 border-t border-gray-800">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-gray-400 text-sm">Metode Pembayaran</span>
                    <span class="text-white text-sm font-medium">
                        {{ $transaction->payment_method_label }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-300 font-semibold">Total Dibayar</span>
                    <span class="text-green-400 font-bold text-xl">
                        ${{ number_format($transaction->total_amount, 2) }}
                    </span>
                </div>
            </div>

        </div>

        {{-- ===== STATUS BADGE ===== --}}
        <div class="flex items-center gap-2 justify-center mb-8">
            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
            <span class="text-green-400 text-sm font-medium">Selesai</span>
            @if ($transaction->completed_at)
                <span class="text-gray-600 text-xs">— {{ $transaction->completed_at->format('d M Y, H:i') }}</span>
            @endif
        </div>

        {{-- ===== ACTION BUTTONS ===== --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('library.index') }}"
               class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-500
                      text-white font-semibold text-sm px-6 py-3 rounded-xl transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Buka Library
            </a>

            <a href="{{ route('store') }}"
               class="flex items-center justify-center gap-2 bg-gray-800 hover:bg-gray-700 border border-gray-700
                      text-gray-300 hover:text-white font-semibold text-sm px-6 py-3 rounded-xl transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                Kembali ke Store
            </a>
        </div>

        {{-- Footer note --}}
        <p class="text-center text-gray-600 text-xs mt-8">
            Ini adalah simulasi transaksi untuk keperluan akademis.
            Tidak ada pembayaran finansial yang diproses.
        </p>

    </div>
</div>
@endsection
