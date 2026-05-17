@extends('layouts.app')

@section('title', 'Checkout — Epic Games')

@section('content')
{{--
    Checkout Epic Games asli tampil sebagai MODAL di atas halaman cart.
    Kita simulasikan dengan full-page overlay yang mirip modal.
--}}
<div class="min-h-screen bg-[#121212] flex items-center justify-center py-10 relative">

    {{-- Background blur (simulasi halaman cart di belakang) --}}
    <div class="absolute inset-0 bg-[#121212]/80 backdrop-blur-sm z-0"></div>

    {{-- ===== MODAL CHECKOUT ===== --}}
    <div class="relative z-10 w-full max-w-4xl mx-4 bg-[#1A1A22] rounded-2xl overflow-hidden shadow-2xl shadow-black/60
                border border-white/10 flex flex-col md:flex-row min-h-[500px]">

        {{-- ===== KIRI: Item Summary ===== --}}
        <div class="md:w-96 bg-[#0d0d14] p-8 flex flex-col border-r border-white/5">

            {{-- Header --}}
            <div class="flex items-center gap-3 mb-8">
                <div class="w-8 h-8 bg-[#26bbff] rounded flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                </div>
                <h2 class="text-white font-bold text-lg">Checkout</h2>
            </div>

            {{-- Game cover + info --}}
            @php $firstGame = $cartGames->first(); @endphp
            @if ($firstGame)
            <div class="flex items-start gap-4 mb-5">
                <div class="w-20 h-24 rounded-lg overflow-hidden bg-gray-800 flex-shrink-0">
                    @if ($firstGame->cover_image_url)
                        <img src="{{ $firstGame->cover_image_url }}"
                             alt="{{ $firstGame->title }}"
                             class="w-full h-full object-cover">
                    @endif
                </div>
                <div>
                    <p class="text-white font-semibold text-sm">{{ $firstGame->title }}</p>
                    @if ($cartGames->count() > 1)
                        <button class="text-gray-400 hover:text-white text-xs mt-1 flex items-center gap-1 transition-colors">
                            + {{ $cartGames->count() - 1 }} more
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    @endif

                    {{-- Saving badge --}}
                    @php $savings = $cartGames->sum('base_price') - $cartGames->sum('final_price'); @endphp
                    @if ($savings > 0)
                        <span class="inline-block mt-2 bg-green-600/20 border border-green-600/40 text-green-400 text-xs px-2 py-0.5 rounded">
                            Saving Rp {{ number_format($savings, 0, ',', '.') }}
                        </span>
                    @endif
                </div>
            </div>
            @endif

            <div class="border-t border-white/10 my-4"></div>

            {{-- Price breakdown --}}
            <div class="space-y-3 text-sm mb-4">
                <div class="flex justify-between">
                    <span class="text-gray-400">Subtotal</span>
                    <span class="text-white">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">VAT included (11%)</span>
                    <span class="text-white">Rp {{ number_format($total * 0.11, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="border-t border-white/10 my-4"></div>

            {{-- Total --}}
            <div class="flex justify-between items-center mb-6">
                <span class="text-white font-bold text-base">Total</span>
                <span class="text-white font-bold text-2xl">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </span>
            </div>

            {{-- Epic Rewards statis --}}
            <div class="bg-[#26bbff]/10 border border-[#26bbff]/20 rounded-lg px-3 py-2 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#26bbff] flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                <span class="text-[#26bbff] text-xs">
                    Get Rp {{ number_format($total * 0.05, 0, ',', '.') }} in Epic Rewards.
                </span>
            </div>
        </div>

        {{-- ===== KANAN: Payment Details ===== --}}
        <div class="flex-1 p-8 flex flex-col">

            {{-- Close button → kembali ke cart --}}
            <div class="flex justify-between items-start mb-8">
                <div>
                    <p class="text-gray-400 text-sm mb-0.5">Logged in as</p>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center text-xs font-bold text-white">
                            {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                        </div>
                        <span class="text-white text-sm font-medium">{{ auth()->user()->username }}</span>
                    </div>
                </div>
                <a href="{{ route('cart.index') }}"
                   class="text-gray-500 hover:text-white transition-colors p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
            </div>

            <h3 class="text-white font-bold text-base mb-5">Payment Details</h3>

            {{-- Flash error --}}
            @if (session('error'))
                <div class="mb-4 bg-red-900/40 border border-red-700/60 text-red-300 px-4 py-3 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf

                {{-- ===== PAYMENT METHOD ===== --}}
                <div class="space-y-2 mb-6">

                    {{-- Epic Rewards (statis, disable) --}}
                    <label class="flex items-center justify-between bg-[#0d0d14] border border-white/10 rounded-xl px-4 py-3.5 cursor-not-allowed opacity-60">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-[#26bbff] rounded flex items-center justify-center">
                                <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                            <span class="text-white text-sm font-medium">Epic Rewards</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </label>

                    {{-- PayPal --}}
                    <label class="flex items-center gap-3 bg-[#0d0d14] border border-white/10 rounded-xl px-4 py-3.5 cursor-pointer
                                  hover:border-white/20 transition-colors has-[:checked]:border-[#26bbff]">
                        <input type="radio" name="payment_method" value="paypal"
                               class="accent-[#26bbff]"
                               {{ old('payment_method') === 'paypal' ? 'checked' : '' }}>
                        <div class="w-8 h-8 bg-[#003087] rounded flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-black text-xs">P</span>
                        </div>
                        <span class="text-white text-sm font-medium">PayPal</span>
                    </label>

                    {{-- Credit Card --}}
                    <label class="flex items-center gap-3 bg-[#0d0d14] border border-white/10 rounded-xl px-4 py-3.5 cursor-pointer
                                  hover:border-white/20 transition-colors has-[:checked]:border-[#26bbff]">
                        <input type="radio" name="payment_method" value="credit_card"
                               class="accent-[#26bbff]"
                               {{ old('payment_method', 'credit_card') === 'credit_card' ? 'checked' : '' }}>
                        <div class="w-8 h-8 bg-gray-700 rounded flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <span class="text-white text-sm font-medium">Credit Card</span>
                    </label>

                    {{-- Debit Card --}}
                    <label class="flex items-center gap-3 bg-[#0d0d14] border border-white/10 rounded-xl px-4 py-3.5 cursor-pointer
                                  hover:border-white/20 transition-colors has-[:checked]:border-[#26bbff]">
                        <input type="radio" name="payment_method" value="debit_card"
                               class="accent-[#26bbff]"
                               {{ old('payment_method') === 'debit_card' ? 'checked' : '' }}>
                        <div class="w-8 h-8 bg-gray-700 rounded flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <span class="text-white text-sm font-medium">Debit Card</span>
                    </label>

                    {{-- Gift Card --}}
                    <label class="flex items-center gap-3 bg-[#0d0d14] border border-white/10 rounded-xl px-4 py-3.5 cursor-pointer
                                  hover:border-white/20 transition-colors has-[:checked]:border-[#26bbff]">
                        <input type="radio" name="payment_method" value="gift_card"
                               class="accent-[#26bbff]"
                               {{ old('payment_method') === 'gift_card' ? 'checked' : '' }}>
                        <div class="w-8 h-8 bg-gray-700 rounded flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                            </svg>
                        </div>
                        <span class="text-white text-sm font-medium">Gift Card</span>
                    </label>

                    @error('payment_method')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Spacer --}}
                <div class="flex-1"></div>

                {{-- Pay Now button --}}
                <button type="submit"
                        class="w-full bg-[#26bbff] hover:bg-[#56cbff] text-black font-bold py-4 rounded-xl
                               transition-colors duration-200 text-base mb-4">
                    Pay Now
                </button>

                {{-- Legal note --}}
                <p class="text-gray-600 text-xs leading-relaxed text-center">
                    By selecting 'Pay Now', you certify that you are over 18, are authorized to use this
                    payment method, and agree to the
                    <span class="text-[#26bbff]">End User License Agreement</span>.
                </p>
                <p class="text-gray-600 text-xs leading-relaxed text-center mt-1">
                    You are paying for a digital license for this product; for terms, see
                    <span class="text-[#26bbff]">purchase policy</span>.
                </p>
                <p class="text-center text-gray-700 text-xs mt-3">
                    ⚠ Simulasi akademis — tidak ada transaksi finansial nyata.
                </p>
            </form>
        </div>

    </div>
</div>
@endsection