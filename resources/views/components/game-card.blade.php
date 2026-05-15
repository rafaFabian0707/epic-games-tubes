{{--
    Komponen: game-card
    Props: $game (Game model dengan eager loaded: publisher, discounts, platforms)
--}}
<div class="group bg-gray-900 rounded-xl overflow-hidden border border-gray-800 hover:border-gray-600
            transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-black/40">
    <a href="{{ route('game.show', $game->game_id) }}" class="block">

        {{-- Cover image --}}
        <div class="relative overflow-hidden aspect-[3/4]">
            @if($game->cover_image_url)
            <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                 loading="lazy">
            @else
            <div class="w-full h-full bg-gray-800 flex items-center justify-center">
                <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            @endif

            {{-- Info badge (First Run, Now On Epic, dll.) --}}
            @if($game->info)
            <span class="absolute top-2 left-2 bg-blue-600/90 backdrop-blur-sm text-white text-xs px-2 py-0.5 rounded-full font-medium">
                {{ $game->info_label }}
            </span>
            @endif

            {{-- Discount badge --}}
            @if($game->discount_pct > 0)
            <span class="absolute top-2 right-2 bg-green-600 text-white text-xs px-2 py-0.5 rounded font-bold">
                -{{ $game->discount_pct }}%
            </span>
            @endif
        </div>

        {{-- Card body --}}
        <div class="p-3">
            <h3 class="font-semibold text-sm leading-tight line-clamp-2 mb-1 group-hover:text-blue-400 transition-colors">
                {{ $game->title }}
            </h3>

            <p class="text-gray-500 text-xs mb-2 truncate">
                {{ $game->publisher?->name ?? 'Unknown Publisher' }}
            </p>

            {{-- Platform badges --}}
            @if($game->relationLoaded('platforms') && $game->platforms->count())
            <div class="flex gap-1 flex-wrap mb-2">
                @foreach($game->platforms as $platform)
                <span class="text-gray-600 text-xs bg-gray-800 px-1.5 py-0.5 rounded">
                    {{ $platform->platform }}
                </span>
                @endforeach
            </div>
            @endif

            {{-- Price --}}
            <div class="mt-2">
                @if($game->final_price == 0)
                <span class="text-green-400 font-bold text-sm">GRATIS</span>
                @elseif($game->discount_pct > 0)
                <div class="flex items-center gap-2">
                    <span class="text-gray-500 text-xs line-through">${{ number_format($game->base_price, 2) }}</span>
                    <span class="text-white font-bold text-sm">${{ number_format($game->final_price, 2) }}</span>
                </div>
                @else
                <span class="text-white font-bold text-sm">${{ number_format($game->base_price, 2) }}</span>
                @endif
            </div>
        </div>
    </a>
</div>
