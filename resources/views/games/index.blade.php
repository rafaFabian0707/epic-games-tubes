@extends('layouts.app')

@section('title', 'Store')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Page header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold">Discover Games</h1>
        <p class="text-gray-400 text-sm mt-1">{{ $games->total() }} game ditemukan</p>
    </div>

    <div class="flex gap-6">

        {{-- ===== SIDEBAR FILTER ===== --}}
        <aside class="hidden lg:block w-56 flex-shrink-0 space-y-6">

            {{-- Price filter --}}
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Harga</h3>
                <div class="space-y-1.5">
                    @foreach([''=>'Semua', 'free'=>'Gratis', 'under10'=>'Di bawah $10', 'under30'=>'Di bawah $30', 'under60'=>'Di bawah $60'] as $val => $label)
                    <a href="{{ request()->fullUrlWithQuery(['price' => $val, 'page' => 1]) }}"
                       class="block text-sm px-3 py-1.5 rounded-lg transition-colors {{ request('price') == $val ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        {{ $label }}
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Genre filter --}}
            @if($genres->count())
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Genre</h3>
                <div class="space-y-1.5 max-h-48 overflow-y-auto">
                    @foreach($genres as $genre)
                    <a href="{{ request()->fullUrlWithQuery(['genre' => $genre->genre_id, 'page' => 1]) }}"
                       class="block text-sm px-3 py-1.5 rounded-lg transition-colors {{ request('genre') == $genre->genre_id ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        {{ $genre->name }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Platform filter --}}
            @if($platforms->count())
            <div>
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Platform</h3>
                <div class="space-y-1.5">
                    @foreach($platforms as $platform)
                    <a href="{{ request()->fullUrlWithQuery(['platform' => $platform->platform_id, 'page' => 1]) }}"
                       class="block text-sm px-3 py-1.5 rounded-lg transition-colors {{ request('platform') == $platform->platform_id ? 'bg-blue-600 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                        {{ $platform->platform }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Reset filter --}}
            @if(request()->hasAny(['genre','price','platform','feature','sort']))
            <a href="{{ route('store') }}" class="block text-sm text-center text-red-400 hover:text-red-300 py-1.5 border border-red-800 rounded-lg hover:border-red-600 transition-colors">
                Reset Filter
            </a>
            @endif
        </aside>

        {{-- ===== GAME GRID ===== --}}
        <div class="flex-1 min-w-0">

            {{-- Sort bar --}}
            <div class="flex items-center justify-between mb-5">
                <div class="text-sm text-gray-400">
                    @if(request()->hasAny(['genre','price','platform']))
                        <span class="text-blue-400">Filter aktif</span> ·
                    @endif
                    Halaman {{ $games->currentPage() }} dari {{ $games->lastPage() }}
                </div>
                <select onchange="window.location=this.value"
                        class="bg-gray-800 border border-gray-700 text-sm rounded-lg px-3 py-1.5 focus:outline-none focus:border-blue-500 cursor-pointer">
                    @foreach(['newest'=>'Terbaru', 'rating'=>'Rating Tertinggi', 'price_asc'=>'Harga Terendah', 'price_desc'=>'Harga Tertinggi', 'name'=>'A-Z'] as $val => $label)
                    <option value="{{ request()->fullUrlWithQuery(['sort' => $val]) }}" {{ request('sort', 'newest') == $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Grid --}}
            @if($games->isEmpty())
            <div class="text-center py-20 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p>Tidak ada game yang ditemukan.</p>
            </div>
            @else
            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($games as $game)
                @include('components.game-card', ['game' => $game])
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $games->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
