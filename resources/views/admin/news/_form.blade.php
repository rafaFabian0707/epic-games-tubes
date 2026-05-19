{{-- resources/views/admin/news/_form.blade.php --}}
@php $isEdit = isset($news) && $news->exists; @endphp

<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- Kiri: konten utama --}}
    <div class="xl:col-span-2 space-y-5">

        {{-- Info Dasar --}}
        <div class="panel">
            <h3 class="panel-section-title">Informasi Artikel</h3>
            <div class="space-y-4">

                <div>
                    <label class="adm-label">Judul Berita <span class="text-red-400">*</span></label>
                    <input type="text" name="title"
                           value="{{ old('title', $news->title ?? '') }}"
                           placeholder="Masukkan judul berita..."
                           class="adm-input @error('title') border-red-500 @enderror">
                    @error('title')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="adm-label">Publisher / Penulis <span class="text-red-400">*</span></label>
                        <input type="text" name="publisher"
                               value="{{ old('publisher', $news->publisher ?? '') }}"
                               placeholder="Nama penulis atau sumber..."
                               class="adm-input @error('publisher') border-red-500 @enderror">
                        @error('publisher')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="adm-label">Tanggal Artikel</label>
                        <input type="text" name="date"
                               value="{{ old('date', $news->date ?? '') }}"
                               placeholder="Misal: May 19, 2026"
                               class="adm-input">
                        <p class="text-[10px] text-gray-600 mt-1">Format bebas, contoh: "May 19, 2026"</p>
                    </div>
                </div>

                <div>
                    <label class="adm-label">URL Cover / Thumbnail <span class="text-red-400">*</span></label>
                    <input type="url" name="cover_url"
                           value="{{ old('cover_url', $news->cover_url ?? '') }}"
                           placeholder="https://..."
                           class="adm-input @error('cover_url') border-red-500 @enderror">
                    @error('cover_url')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    @if($isEdit && $news->cover_url)
                    <div class="mt-2 flex items-center gap-3 p-2 bg-[#0d0d0f] rounded-xl border border-[#1e1e22]">
                        <img src="{{ $news->cover_url }}" alt="Cover"
                             class="h-12 w-20 object-cover rounded-lg ring-1 ring-white/10"
                             onerror="this.style.display='none'">
                        <p class="text-xs text-gray-500">Preview cover saat ini</p>
                    </div>
                    @endif
                </div>

                <div>
                    <label class="adm-label">URL Media Tambahan (Video/Gambar)</label>
                    <input type="url" name="media_url"
                           value="{{ old('media_url', $news->media_url ?? '') }}"
                           placeholder="https://..."
                           class="adm-input">
                </div>

            </div>
        </div>

        {{-- Deskripsi --}}
        <div class="panel">
            <h3 class="panel-section-title">Konten</h3>
            <div class="space-y-4">
                <div>
                    <label class="adm-label">Ringkasan / Intro (main_content)</label>
                    <textarea name="main_content" rows="3"
                              placeholder="Ringkasan singkat artikel yang tampil di card..."
                              class="adm-input resize-y">{{ old('main_content', $news->main_content ?? '') }}</textarea>
                    <p class="text-[10px] text-gray-600 mt-1">Maks. 3000 karakter — tampil sebagai preview card</p>
                </div>
                <div>
                    <label class="adm-label">Isi Lengkap Artikel <span class="text-red-400">*</span></label>
                    <textarea name="content" rows="12"
                              placeholder="Tulis isi artikel lengkap di sini..."
                              class="adm-input resize-y @error('content') border-red-500 @enderror">{{ old('content', $news->content ?? '') }}</textarea>
                    @error('content')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

    </div>

    {{-- Kanan: status --}}
    <div class="space-y-5">

        {{-- Status --}}
        <div class="panel">
            <h3 class="panel-section-title">Status Publikasi</h3>
            <label class="flex items-center gap-3 cursor-pointer select-none group mt-2">
                <div class="relative">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $news->is_active ?? true) ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="w-10 h-6 bg-[#1e1e22] rounded-full peer-checked:bg-[#0078f2] transition-colors"></div>
                    <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                </div>
                <div>
                    <p class="text-sm font-medium text-white">Tampilkan ke publik</p>
                    <p class="text-[11px] text-gray-500">Matikan untuk menyembunyikan artikel</p>
                </div>
            </label>
        </div>

        {{-- Tombol --}}
        <div class="flex flex-col gap-2">
            <button type="submit"
                    class="w-full bg-[#0078f2] hover:bg-[#0063cc] text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-[#0078f2]/20 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ $isEdit ? 'Simpan Perubahan' : 'Publikasikan Berita' }}
            </button>
            <a href="{{ route('admin.news.index') }}"
               class="w-full border border-[#2a2a30] hover:border-gray-500 text-gray-400 hover:text-white font-medium py-3 rounded-xl transition-all text-center text-sm">
                Batal
            </a>
        </div>

        {{-- Danger zone (hanya edit) --}}
        @if($isEdit)
        <div class="panel border-red-900/30">
            <h3 class="text-sm font-semibold text-red-400 mb-3">Hapus Permanen</h3>
            <p class="text-xs text-gray-500 mb-3">Berita akan dihapus permanen dari database.</p>
            <form method="POST" action="{{ route('admin.news.destroy', $news->news_id) }}"
                  onsubmit="return confirm('Hapus permanen berita \'{{ addslashes($news->title) }}\'?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="w-full bg-red-900/20 hover:bg-red-900/40 border border-red-700/40 text-red-400 text-sm font-semibold py-2.5 rounded-xl transition-all">
                    Hapus Berita
                </button>
            </form>
        </div>
        @endif

    </div>
</div>

<style>
    .panel { @apply bg-[#111114] border border-[#1e1e22] rounded-2xl p-5; }
    .panel-section-title { @apply text-sm font-semibold text-white mb-4 flex items-center gap-2; }
    .panel-section-title::before { content:''; @apply block w-1 h-4 bg-[#0078f2] rounded-full; }
    .adm-label { @apply block text-xs font-medium text-gray-400 mb-1.5; }
    .adm-input { @apply w-full bg-[#0d0d0f] border border-[#2a2a30] rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-[#0078f2] focus:ring-1 focus:ring-[#0078f2]/30 transition-all; }
    select.adm-input option { background-color: #111114; }
</style>
