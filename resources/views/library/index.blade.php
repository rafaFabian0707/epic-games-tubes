@extends('layouts.app')

@section('title', 'Library — Epic Games')

@section('content')
<div class="min-h-screen bg-gray-950 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ===== PAGE HEADER ===== --}}
        <div class="flex items-end justify-between mb-8 flex-wrap gap-4">
            <div>
                <h1 class="text-2xl font-bold text-white tracking-tight">Library</h1>
                <p class="text-gray-400 text-sm mt-1">
                    {{ $libraryItems->count() }} game dalam koleksimu
                </p>
            </div>

            {{-- Search lokal (client-side filter) --}}
            @if ($libraryItems->count() > 6)
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 pointer-events-none"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           id="library-search"
                           placeholder="Filter game..."
                           class="bg-gray-900 border border-gray-700 text-sm text-white rounded-lg pl-9 pr-4 py-2
                                  focus:outline-none focus:border-blue-500 placeholder-gray-500 w-48 transition-all
                                  focus:w-64">
                </div>
            @endif
        </div>

        {{-- ===== EMPTY STATE ===== --}}
        @if ($libraryItems->isEmpty())
            <div class="flex flex-col items-center justify-center py-24 text-center">
                <div class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-white mb-2">Library-mu masih kosong</h2>
                <p class="text-gray-400 text-sm mb-8 max-w-xs">
                    Game yang kamu beli akan otomatis muncul di sini setelah transaksi selesai.
                </p>
                <a href="{{ route('store') }}"
                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500
                          text-white font-semibold text-sm px-6 py-3 rounded-xl transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    Kunjungi Store
                </a>
            </div>

        {{-- ===== LIBRARY GRID ===== --}}
        @else
            <div id="library-grid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                @foreach ($libraryItems as $item)
                    @php $game = $item->game; @endphp
                    @if ($game)
                        <a href="{{ route('game.show', $game->game_id) }}"
                           data-title="{{ strtolower($game->title) }}"
                           class="library-card group flex flex-col bg-gray-900 border border-gray-800 rounded-xl overflow-hidden
                                  hover:border-gray-600 hover:-translate-y-1 hover:shadow-lg hover:shadow-black/40
                                  transition-all duration-300">

                            {{-- Cover --}}
                            <div class="relative aspect-[3/4] overflow-hidden bg-gray-800">
                                @if ($game->cover_image_url)
                                    <img src="{{ $game->cover_image_url }}"
                                         alt="{{ $game->title }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center gap-2 p-3">
                                        <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                  d="M15 10l4.553-2.069A1 1 0 0121 8.87v6.26a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                                        </svg>
                                        <span class="text-gray-600 text-xs text-center leading-tight">{{ $game->title }}</span>
                                    </div>
                                @endif

                                {{-- Overlay on hover --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent
                                            opacity-0 group-hover:opacity-100 transition-opacity duration-300
                                            flex items-end p-2">
                                    <span class="text-white text-xs font-semibold bg-blue-600 px-2 py-1 rounded-lg w-full text-center">
                                        Lihat Detail
                                    </span>
                                </div>

                                {{-- Age rating badge --}}
                                @if ($game->ageRating)
                                    <div class="absolute top-1.5 right-1.5">
                                        <span class="bg-gray-900/80 backdrop-blur-sm text-gray-300 text-xs px-1.5 py-0.5 rounded border border-gray-700">
                                            {{ $game->ageRating->rating_label ?? 'E' }}
                                        </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="p-2.5">
                                <p class="text-white text-xs font-semibold leading-snug line-clamp-2 mb-1">
                                    {{ $game->title }}
                                </p>
                                @if ($game->publisher)
                                    <p class="text-gray-600 text-xs truncate">{{ $game->publisher->name }}</p>
                                @endif

                                {{-- Platform tags --}}
                                @if ($game->platforms && $game->platforms->count())
                                    <div class="flex flex-wrap gap-1 mt-1.5">
                                        @foreach ($game->platforms->take(2) as $platform)
                                            <span class="text-gray-500 text-xs">{{ $platform->name }}</span>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Acquired date --}}
                                <p class="text-gray-700 text-xs mt-1.5">
                                    {{ $item->acquired_at ? $item->acquired_at->format('d M Y') : '-' }}
                                </p>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>

            {{-- Empty filter result message (hidden by default) --}}
            <div id="library-empty-filter" class="hidden text-center py-16">
                <p class="text-gray-500 text-sm">Tidak ada game yang cocok dengan pencarianmu.</p>
            </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
    // Client-side filter untuk library
    const searchInput = document.getElementById('library-search');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const keyword = this.value.toLowerCase().trim();
            const cards   = document.querySelectorAll('.library-card');
            const emptyMsg = document.getElementById('library-empty-filter');
            let visibleCount = 0;

            cards.forEach(card => {
                const title = card.dataset.title || '';
                if (title.includes(keyword)) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            emptyMsg.classList.toggle('hidden', visibleCount > 0);
        });
    }
</script>
@endpush
@endsection
