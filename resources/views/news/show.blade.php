@extends('layouts.app')

@section('title', $article->title . ' — Epic Games News')

@section('content')
<div class="min-h-screen bg-gray-950 py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ===== BREADCRUMB ===== --}}
        <nav class="flex items-center gap-2 text-xs text-gray-500 mb-8">
            <a href="{{ route('home') }}" class="hover:text-gray-300 transition-colors">Home</a>
            <span>/</span>
            <a href="{{ route('news.index') }}" class="hover:text-gray-300 transition-colors">News</a>
            <span>/</span>
            <span class="text-gray-400 truncate max-w-[200px]">{{ $article->title }}</span>
        </nav>

        {{-- ===== ARTICLE HEADER ===== --}}
        <header class="mb-8">
            {{-- Meta info --}}
            <div class="flex items-center gap-3 mb-4 flex-wrap">
                <span class="bg-blue-600/20 border border-blue-600/40 text-blue-400 text-xs font-semibold px-3 py-1 rounded-full">
                    Epic Games News
                </span>
                @if ($article->date)
                    <span class="text-gray-500 text-xs flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $article->date }}
                    </span>
                @endif
                <span class="text-gray-500 text-xs flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    {{ $article->publisher }}
                </span>
            </div>

            {{-- Judul --}}
            <h1 class="text-3xl md:text-4xl font-bold text-white leading-tight mb-4">
                {{ $article->title }}
            </h1>

            {{-- Intro / main_content --}}
            @if ($article->main_content)
                <p class="text-gray-300 text-lg leading-relaxed border-l-4 border-blue-600 pl-4 italic">
                    {{ $article->main_content }}
                </p>
            @endif
        </header>

        {{-- ===== COVER IMAGE ===== --}}
        @if ($article->cover_url)
            <div class="rounded-2xl overflow-hidden mb-8 bg-gray-800">
                <img src="{{ $article->cover_url }}"
                     alt="{{ $article->title }}"
                     class="w-full h-64 md:h-96 object-cover">
            </div>
        @endif

        {{-- ===== ARTICLE BODY ===== --}}
        <article class="prose prose-invert prose-sm md:prose-base max-w-none mb-10">
            {{--
                Jika konten berupa plain text: tampilkan dengan nl2br.
                Jika konten berupa HTML: gunakan {!! $article->content !!} (pastikan sudah di-sanitize).
                Defaultnya kita pakai nl2br untuk keamanan.
            --}}
            <div class="text-gray-300 leading-relaxed space-y-4">
                @foreach (explode("\n\n", $article->content) as $paragraph)
                    @if (trim($paragraph))
                        <p>{{ trim($paragraph) }}</p>
                    @endif
                @endforeach
            </div>
        </article>

        {{-- ===== MEDIA (video/gambar tambahan) ===== --}}
        @if ($article->media_url)
            <div class="mb-10">
                <h3 class="text-white font-semibold text-sm mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Media Terkait
                </h3>
                @php
                    // Cek apakah media_url adalah embed YouTube
                    $isYoutube = str_contains($article->media_url, 'youtube.com') || str_contains($article->media_url, 'youtu.be');
                    // Konversi ke embed URL jika perlu
                    $embedUrl = $article->media_url;
                    if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $article->media_url, $m)) {
                        $embedUrl = 'https://www.youtube.com/embed/' . $m[1];
                        $isYoutube = true;
                    } elseif (preg_match('/youtu\.be\/([^?]+)/', $article->media_url, $m)) {
                        $embedUrl = 'https://www.youtube.com/embed/' . $m[1];
                        $isYoutube = true;
                    }
                @endphp

                @if ($isYoutube)
                    <div class="relative w-full aspect-video rounded-xl overflow-hidden bg-gray-800">
                        <iframe src="{{ $embedUrl }}"
                                class="absolute inset-0 w-full h-full"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                        </iframe>
                    </div>
                @else
                    {{-- Anggap sebagai gambar --}}
                    <div class="rounded-xl overflow-hidden bg-gray-800">
                        <img src="{{ $article->media_url }}"
                             alt="Media terkait {{ $article->title }}"
                             class="w-full h-auto max-h-96 object-cover">
                    </div>
                @endif
            </div>
        @endif

        {{-- ===== DIVIDER ===== --}}
        <div class="border-t border-gray-800 my-8"></div>

        {{-- ===== FOOTER ARTICLE ===== --}}
        <div class="flex items-center justify-between flex-wrap gap-4">
            <a href="{{ route('news.index') }}"
               class="inline-flex items-center gap-2 text-gray-400 hover:text-white text-sm transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Semua Berita
            </a>

            <div class="flex items-center gap-3">
                <span class="text-gray-600 text-xs">Bagikan:</span>
                {{-- Share links (tanpa JS external) --}}
                <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(request()->url()) }}"
                   target="_blank" rel="noopener"
                   class="text-gray-500 hover:text-blue-400 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
