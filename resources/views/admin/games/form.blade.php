{{--
    _form.blade.php — Komponen form game (dipakai di create.blade.php & edit.blade.php)

    Variabel yang WAJIB tersedia dari controller:
      $publishers, $developers, $genres, $features, $tags, $platforms, $parentGames
      $game (opsional — null saat create, objek Game saat edit)
      $selectedGenres, $selectedFeatures, $selectedTags, $selectedPlatforms (opsional)
--}}

@php
    $isEdit           = isset($game) && $game->exists;
    $selectedGenres   = $selectedGenres   ?? [];
    $selectedFeatures = $selectedFeatures ?? [];
    $selectedTags     = $selectedTags     ?? [];
    $selectedPlatforms= $selectedPlatforms?? [];

    $gameTypes = [
        'base_game'  => 'Base Game',
        'edition'    => 'Edition',
        'addon'      => 'Add-on / DLC',
        'aplikasi'   => 'Aplikasi',
        'editor'     => 'Editor',
        'langganan'  => 'Langganan',
        'pengalaman' => 'Pengalaman',
        'bundle'     => 'Bundle',
        'demo'       => 'Demo',
    ];
    $refundTypes = [
        'refundable'      => 'Refundable',
        'self_refundable' => 'Self Refundable',
        'non_refundable'  => 'Non Refundable',
    ];
    $infoOptions = [
        ''                => '— Tidak ada —',
        'First_Run'       => 'First Run',
        'Now_On_Epic'     => 'Now on Epic',
        'Trial_Available' => 'Trial Available',
    ];
@endphp

<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- ===================================================== --}}
    {{-- KOLOM KIRI: Informasi Utama                           --}}
    {{-- ===================================================== --}}
    <div class="xl:col-span-2 space-y-5">

        {{-- Judul & Tipe --}}
        <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <span class="w-1 h-4 bg-[#0078f2] rounded-full"></span>
                Informasi Dasar
            </h3>
            <div class="space-y-4">

                {{-- Judul --}}
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1.5">
                        Judul Game <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="title"
                           value="{{ old('title', $game->title ?? '') }}"
                           placeholder="Masukkan judul game..."
                           class="admin-input @error('title') border-red-500 @enderror">
                    @error('title')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tipe Game --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">
                            Tipe Game <span class="text-red-400">*</span>
                        </label>
                        <select name="game_type" class="admin-input @error('game_type') border-red-500 @enderror">
                            @foreach($gameTypes as $val => $label)
                            <option value="{{ $val }}"
                                {{ old('game_type', $game->game_type ?? 'base_game') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                        @error('game_type')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Label Info --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">Label Info</label>
                        <select name="info" class="admin-input">
                            @foreach($infoOptions as $val => $label)
                            <option value="{{ $val }}"
                                {{ old('info', $game->info ?? '') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Parent Game (muncul jika bukan base_game) --}}
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1.5">Game Induk (Parent)</label>
                    <select name="parent_game_id" class="admin-input">
                        <option value="">— Tidak ada —</option>
                        @foreach($parentGames as $pg)
                        <option value="{{ $pg->game_id }}"
                            {{ old('parent_game_id', $game->parent_game_id ?? '') == $pg->game_id ? 'selected' : '' }}>
                            {{ $pg->title }}
                        </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        {{-- Deskripsi --}}
        <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <span class="w-1 h-4 bg-[#0078f2] rounded-full"></span>
                Deskripsi
            </h3>
            <div class="space-y-4">

                {{-- Main Desc --}}
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1.5">Deskripsi Singkat (main_desc)</label>
                    <textarea name="main_desc" rows="3"
                              placeholder="Deskripsi singkat yang muncul di kartu game..."
                              class="admin-input resize-y">{{ old('main_desc', $game->main_desc ?? '') }}</textarea>
                </div>

                {{-- Announce --}}
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1.5">Pengumuman / Highlight (announce)</label>
                    <textarea name="announce" rows="2"
                              placeholder="Teks pengumuman kecil di halaman game..."
                              class="admin-input resize-y">{{ old('announce', $game->announce ?? '') }}</textarea>
                </div>

                {{-- Full Desc --}}
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1.5">Deskripsi Lengkap (desc)</label>
                    <textarea name="desc" rows="5"
                              placeholder="Deskripsi panjang, fitur utama, dan lainnya..."
                              class="admin-input resize-y">{{ old('desc', $game->desc ?? '') }}</textarea>
                </div>

            </div>
        </div>

        {{-- Media --}}
        <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <span class="w-1 h-4 bg-violet-500 rounded-full"></span>
                Media & URL
            </h3>
            <div class="space-y-4">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Cover Image --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">URL Cover Image</label>
                        <input type="url" name="cover_image_url"
                               value="{{ old('cover_image_url', $game->cover_image_url ?? '') }}"
                               placeholder="https://..."
                               class="admin-input @error('cover_image_url') border-red-500 @enderror">
                        @error('cover_image_url')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- Icon --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">URL Icon</label>
                        <input type="url" name="icon_url"
                               value="{{ old('icon_url', $game->icon_url ?? '') }}"
                               placeholder="https://..."
                               class="admin-input">
                    </div>
                </div>

                {{-- Media (trailer/video) --}}
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1.5">URL Media (Trailer/Video)</label>
                    <input type="url" name="media_url"
                           value="{{ old('media_url', $game->media_url ?? '') }}"
                           placeholder="https://youtube.com/..."
                           class="admin-input @error('media_url') border-red-500 @enderror">
                    @error('media_url')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Preview cover --}}
                @if($isEdit && $game->cover_image_url)
                <div class="flex items-center gap-3 p-3 bg-[#0d0d0f] rounded-xl border border-[#1e1e22]">
                    <img src="{{ $game->cover_image_url }}" alt="Cover"
                         class="w-12 h-16 object-cover rounded-lg ring-1 ring-white/10"
                         onerror="this.style.display='none'">
                    <p class="text-xs text-gray-500">Preview cover saat ini</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Relasi: Genres, Features, Tags, Platforms --}}
        <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <span class="w-1 h-4 bg-cyan-500 rounded-full"></span>
                Kategori & Platform
            </h3>
            <div class="space-y-5">

                {{-- Genres --}}
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2">Genre</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($genres as $genre)
                        <label class="flex items-center gap-1.5 cursor-pointer select-none">
                            <input type="checkbox" name="genres[]" value="{{ $genre->genre_id }}"
                                   {{ in_array($genre->genre_id, old('genres', $selectedGenres)) ? 'checked' : '' }}
                                   class="admin-checkbox">
                            <span class="text-xs text-gray-400 hover:text-white transition-colors">{{ $genre->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Platforms --}}
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2">Platform</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($platforms as $platform)
                        <label class="flex items-center gap-1.5 cursor-pointer select-none">
                            <input type="checkbox" name="platforms[]" value="{{ $platform->platform_id }}"
                                   {{ in_array($platform->platform_id, old('platforms', $selectedPlatforms)) ? 'checked' : '' }}
                                   class="admin-checkbox">
                            <span class="text-xs text-gray-400 hover:text-white transition-colors">{{ $platform->platform }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Features --}}
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2">Fitur</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($features as $feature)
                        <label class="flex items-center gap-1.5 cursor-pointer select-none">
                            <input type="checkbox" name="features[]" value="{{ $feature->feature_id }}"
                                   {{ in_array($feature->feature_id, old('features', $selectedFeatures)) ? 'checked' : '' }}
                                   class="admin-checkbox">
                            <span class="text-xs text-gray-400 hover:text-white transition-colors">{{ $feature->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Tags --}}
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-2">Tags</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($tags as $tag)
                        <label class="flex items-center gap-1.5 cursor-pointer select-none">
                            <input type="checkbox" name="tags[]" value="{{ $tag->tag_id }}"
                                   {{ in_array($tag->tag_id, old('tags', $selectedTags)) ? 'checked' : '' }}
                                   class="admin-checkbox">
                            <span class="text-xs text-gray-400 hover:text-white transition-colors">{{ $tag->label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- ===================================================== --}}
    {{-- KOLOM KANAN: Harga, Developer, Age Rating, Status     --}}
    {{-- ===================================================== --}}
    <div class="space-y-5">

        {{-- Harga & Bisnis --}}
        <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <span class="w-1 h-4 bg-emerald-500 rounded-full"></span>
                Harga & Bisnis
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1.5">
                        Harga Dasar (Rp) <span class="text-red-400">*</span>
                    </label>
                    <input type="number" name="base_price" min="0" step="1000"
                           value="{{ old('base_price', $game->base_price ?? 0) }}"
                           class="admin-input @error('base_price') border-red-500 @enderror">
                    <p class="text-[11px] text-gray-600 mt-1">Isi 0 untuk game gratis</p>
                    @error('base_price')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1.5">Tipe Refund</label>
                    <select name="refund_type" class="admin-input">
                        <option value="">— Pilih —</option>
                        @foreach($refundTypes as $val => $label)
                        <option value="{{ $val }}"
                            {{ old('refund_type', $game->refund_type ?? '') === $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1.5">Tanggal Rilis</label>
                    <input type="date" name="release_date"
                           value="{{ old('release_date', optional($game->release_date ?? null)->format('Y-m-d')) }}"
                           class="admin-input">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1.5">Rating (0–5)</label>
                    <input type="number" name="avg_rating" min="0" max="5" step="0.1"
                           value="{{ old('avg_rating', $game->avg_rating ?? '') }}"
                           placeholder="4.5"
                           class="admin-input">
                </div>
            </div>
        </div>

        {{-- Publisher & Developer --}}
        <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <span class="w-1 h-4 bg-orange-500 rounded-full"></span>
                Publisher & Developer
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1.5">Publisher</label>
                    <select name="publisher_id" class="admin-input">
                        <option value="">— Pilih publisher —</option>
                        @foreach($publishers as $pub)
                        <option value="{{ $pub->publisher_id }}"
                            {{ old('publisher_id', $game->publisher_id ?? '') == $pub->publisher_id ? 'selected' : '' }}>
                            {{ $pub->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1.5">Developer</label>
                    <select name="developer_id" class="admin-input">
                        <option value="">— Pilih developer —</option>
                        @foreach($developers as $dev)
                        <option value="{{ $dev->developer_id }}"
                            {{ old('developer_id', $game->developer_id ?? '') == $dev->developer_id ? 'selected' : '' }}>
                            {{ $dev->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Age Rating --}}
        <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <span class="w-1 h-4 bg-yellow-500 rounded-full"></span>
                Age Rating
            </h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1.5">Rating (mis: E, T, M, AO)</label>
                    <input type="text" name="age"
                           value="{{ old('age', optional($game->ageRating ?? null)->age) }}"
                           placeholder="E, E10+, T, M, AO..."
                           maxlength="10"
                           class="admin-input">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-400 mb-1.5">Keterangan</label>
                    <input type="text" name="age_desc"
                           value="{{ old('age_desc', optional($game->ageRating ?? null)->desc) }}"
                           placeholder="Everyone, Teen, Mature 17+..."
                           maxlength="50"
                           class="admin-input">
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="bg-[#111114] border border-[#1e1e22] rounded-2xl p-5">
            <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                <span class="w-1 h-4 bg-red-500 rounded-full"></span>
                Status Publikasi
            </h3>
            <label class="flex items-center gap-3 cursor-pointer select-none group">
                <div class="relative">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" id="is_active"
                           {{ old('is_active', $game->is_active ?? true) ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="w-10 h-6 bg-[#1e1e22] rounded-full peer-checked:bg-[#0078f2] transition-colors duration-200"></div>
                    <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200 peer-checked:translate-x-4"></div>
                </div>
                <div>
                    <p class="text-sm font-medium text-white">Aktif & Tampil di Store</p>
                    <p class="text-[11px] text-gray-500">Matikan untuk menyembunyikan dari publik</p>
                </div>
            </label>
        </div>

        {{-- Tombol Submit --}}
        <div class="flex flex-col gap-2">
            <button type="submit"
                    class="w-full bg-[#0078f2] hover:bg-[#0063cc] text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-[#0078f2]/20 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Game' }}
            </button>
            <a href="{{ route('admin.games.index') }}"
               class="w-full border border-[#2a2a30] hover:border-gray-500 text-gray-400 hover:text-white font-medium py-3 rounded-xl transition-all text-center text-sm">
                Batal
            </a>
        </div>

    </div>
</div>

{{-- Shared input styles --}}
<style>
    .admin-input {
        @apply w-full bg-[#0d0d0f] border border-[#2a2a30] rounded-xl px-4 py-2.5 text-sm text-white
               placeholder-gray-600 focus:outline-none focus:border-[#0078f2] focus:ring-1 focus:ring-[#0078f2]/30
               transition-all;
    }
    .admin-checkbox {
        @apply w-4 h-4 rounded bg-[#0d0d0f] border-[#2a2a30] text-[#0078f2]
               focus:ring-[#0078f2]/30 focus:ring-offset-0 cursor-pointer;
    }
    select.admin-input option {
        background-color: #111114;
    }
</style>