@extends('layouts.app')

@section('title', 'Checkout — Epic Games')

@section('content')
<div class="min-h-screen bg-gray-950 py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ===== PAGE HEADER ===== --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('cart.index') }}" class="text-gray-500 hover:text-gray-300 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-white tracking-tight">Checkout</h1>
            </div>
            <p class="text-gray-400 text-sm">Tinjau pesananmu sebelum melanjutkan pembayaran.</p>
        </div>

        {{-- ===== FLASH ERROR ===== --}}
        @if (session('error'))
            <div class="mb-6 flex items-start gap-3 bg-red-900/40 border border-red-700/60 text-red-300 text-sm rounded-xl px-4 py-3">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10A8 8 0 112 10a8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('checkout.process') }}" method="POST">
            @csrf

            <div class="flex flex-col lg:flex-row gap-8">

                {{-- ===== KOLOM KIRI: Item + Pembayaran ===== --}}
                <div class="flex-1 space-y-6">

                    {{-- ---- Item List ---- --}}
                    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-800">
                            <h2 class="text-white font-semibold text-sm">
                                Item yang Dibeli ({{ $cartGames->count() }})
                            </h2>
                        </div>

                        <div class="divide-y divide-gray-800">
                            @foreach ($cartGames as $game)
                                <div class="flex items-center gap-4 px-5 py-4">
                                    {{-- Thumbnail --}}
                                    <div class="w-16 h-10 rounded-lg overflow-hidden bg-gray-800 flex-shrink-0">
                                        @if ($game->cover_image_url)
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

                                    {{-- Info --}}
                                    <div class="flex-1 min-w-0">
                                        <p class="text-white text-sm font-medium truncate">{{ $game->title }}</p>
                                        @if ($game->publisher)
                                            <p class="text-gray-500 text-xs">{{ $game->publisher->name }}</p>
                                        @endif
                                        @if ($game->ageRating)
                                            <span class="inline-block text-xs border border-gray-700 text-gray-400 px-1.5 rounded mt-1">
                                                {{ $game->ageRating->rating_label ?? 'Everyone' }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Harga --}}
                                    <div class="flex-shrink-0 text-right">
                                        @if ($game->discount_pct > 0)
                                            <div class="flex items-center gap-1.5 justify-end">
                                                <span class="bg-green-600 text-white text-xs font-bold px-1 py-0.5 rounded">-{{ $game->discount_pct }}%</span>
                                                <span class="text-gray-500 text-xs line-through">${{ number_format($game->base_price, 2) }}</span>
                                            </div>
                                        @endif
                                        <span class="text-white font-bold text-sm">
                                            @if ($game->final_price == 0)
                                                <span class="text-green-400">Gratis</span>
                                            @else
                                                ${{ number_format($game->final_price, 2) }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ---- Metode Pembayaran ---- --}}
                    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-800">
                            <h2 class="text-white font-semibold text-sm">Metode Pembayaran</h2>
                        </div>

                        <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @php
                                $methods = [
                                    'credit_card' => ['label' => 'Credit Card', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                                    'debit_card'  => ['label' => 'Debit Card',  'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                                    'paypal'      => ['label' => 'PayPal',       'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z'],
                                    'gift_card'   => ['label' => 'Gift Card',    'icon' => 'M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7'],
                                ];
                            @endphp

                            @foreach ($methods as $value => $method)
                                <label class="flex items-center gap-3 bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 cursor-pointer
                                              hover:border-blue-500/60 transition-all duration-200
                                              has-[:checked]:border-blue-500 has-[:checked]:bg-blue-600/10">
                                    <input type="radio" name="payment_method" value="{{ $value }}"
                                           class="accent-blue-500"
                                           {{ old('payment_method') === $value ? 'checked' : ($value === 'credit_card' ? 'checked' : '') }}>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $method['icon'] }}"/>
                                    </svg>
                                    <span class="text-gray-200 text-sm font-medium">{{ $method['label'] }}</span>
                                </label>
                            @endforeach

                            @error('payment_method')
                                <p class="col-span-2 text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                </div>

                {{-- ===== KOLOM KANAN: Order Summary ===== --}}
                <div class="lg:w-72 flex-shrink-0">
                    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 sticky top-20">
                        <h2 class="text-white font-bold text-base mb-5">Ringkasan</h2>

                        {{-- Sub-total per item --}}
                        <div class="space-y-2.5 mb-4">
                            @foreach ($cartGames as $game)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-400 truncate flex-1 min-w-0 mr-2">{{ $game->title }}</span>
                                    <span class="text-white flex-shrink-0">
                                        @if ($game->final_price == 0) Gratis
                                        @else ${{ number_format($game->final_price, 2) }}
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-800 my-4"></div>

                        {{-- Total --}}
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-gray-300 font-semibold">Total</span>
                            <span class="text-white font-bold text-2xl">${{ number_format($total, 2) }}</span>
                        </div>

                        {{-- Catatan simulasi --}}
                        <div class="bg-blue-900/20 border border-blue-800/40 rounded-lg px-3 py-2.5 mb-5">
                            <p class="text-blue-300 text-xs leading-relaxed">
                                <strong>Simulasi:</strong> Pembayaran ini bersifat simulasi akademis.
                                Tidak ada transaksi finansial nyata.
                            </p>
                        </div>

                        {{-- Tombol bayar --}}
                        <button type="submit"
                                class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-500
                                       text-white font-bold text-sm py-3.5 rounded-xl transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 13l4 4L19 7"/>
                            </svg>
                            Konfirmasi Pembayaran
                        </button>

                        <a href="{{ route('cart.index') }}"
                           class="w-full flex justify-center text-gray-500 hover:text-gray-300 text-xs mt-3 transition-colors">
                            ← Kembali ke Keranjang
                        </a>
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>
@endsection
