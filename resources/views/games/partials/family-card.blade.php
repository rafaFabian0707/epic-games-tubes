@php
    $activeDiscount = $item->discounts->filter(function ($discount) {
        return $discount->is_active
            && $discount->start_date <= now()
            && $discount->end_date >= now();
    })->sortByDesc('discount_pct')->first();

    $basePrice = (float) ($item->base_price ?? 0);
    $discountPct = $activeDiscount ? (float) $activeDiscount->discount_pct : 0;
    $finalPrice = $activeDiscount
        ? round($basePrice * (1 - $discountPct / 100))
        : $basePrice;

    $typeLabel = match($item->game_type) {
        'edition' => 'Edition',
        'bundle' => 'Bundle',
        'addon' => 'Add-On',
        'demo' => 'Demo',
        default => ucwords(str_replace('_', ' ', $item->game_type)),
    };
@endphp

<div class="mx-6 flex gap-4 bg-gray-100/10 border border-gray-900/10 rounded-2xl p-6 hover:border-gray-600 hover:bg-gray-200/10 transition-all duration-200 group">
    <a href="{{ route('game.show', $item->game_id) }}"
       class="w-36 aspect-[3/2] rounded-lg overflow-hidden flex-shrink-0 bg-gray-200">
        @if($item->cover_image_url)
            <img src="{{ $item->cover_image_url }}"
                 alt="{{ $item->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="w-full h-full bg-gray-500"></div>
        @endif
    </a>

    <div class="flex-1 min-w-0">
        <p class="text-gray-400 text-xs mb-1">
            {{ $typeLabel }}
        </p>

        <a href="{{ route('game.show', $item->game_id) }}">
            <h4 class="text-white font-bold text-base mb-1 group-hover:text-gray-300 transition-colors leading-snug">
                {{ $item->title }}
            </h4>
        </a>

        @if($item->main_desc)
            <p class="text-gray-500 text-xs leading-relaxed line-clamp-2 mb-3">
                {{ $item->main_desc }}
            </p>
        @endif

        <div class="border-t border-gray-800/60 pt-3">
            <div class="flex items-center gap-2 mb-2">
                @if($activeDiscount)
                    <span class="bg-gray-600/90 text-white text-xs font-bold px-1.5 py-0.5 rounded">
                        -{{ number_format($discountPct, 0) }}%
                    </span>

                    <span class="text-gray-500 text-sm line-through">
                        IDR {{ number_format($basePrice, 0, ',', '.') }}
                    </span>
                @endif

                <span class="text-white font-bold text-base">
                    @if($finalPrice <= 0)
                        Free
                    @else
                        IDR {{ number_format($finalPrice, 0, ',', '.') }}
                    @endif
                </span>
            </div>

            @if($activeDiscount)
                <p class="text-gray-500 text-xs mb-3">
                    Sale ends {{ \Carbon\Carbon::parse($activeDiscount->end_date)->format('d M Y') }}
                </p>
            @endif

            <div class="flex items-center gap-2">
                @auth
                    <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="game_id" value="{{ $item->game_id }}">

                        <button type="submit"
                                class="w-full bg-gray-500 hover:bg-blue-400 active:scale-95 text-white font-semibold py-2 px-4 rounded-xl text-sm transition-all duration-200">
                            Add To Cart
                        </button>
                    </form>

                    <form action="{{ route('wishlist.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="game_id" value="{{ $item->game_id }}">

                        <button type="submit"
                                class="w-9 h-9 bg-gray-800 hover:bg-gray-700 border border-gray-700 hover:border-gray-500 rounded-xl flex items-center justify-center text-gray-400 hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="flex-1 text-center bg-blue-500 hover:bg-blue-400 text-white font-semibold py-2 px-4 rounded-xl text-sm transition-all">
                        Add To Cart
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>