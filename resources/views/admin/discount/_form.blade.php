{{-- resources/views/admin/discounts/_form.blade.php --}}
@php $isEdit = isset($discount) && $discount->exists; @endphp

<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

    {{-- Kiri --}}
    <div class="xl:col-span-2 space-y-5">
        <div class="panel">
            <h3 class="panel-section-title">Detail Diskon</h3>
            <div class="space-y-4">

                {{-- Pilih Game --}}
                <div>
                    <label class="adm-label">Game <span class="text-red-400">*</span></label>
                    <select name="game_id" class="adm-input @error('game_id') border-red-500 @enderror"
                            id="gameSelect">
                        <option value="">— Pilih game —</option>
                        @foreach($games as $g)
                        <option value="{{ $g->game_id }}"
                                data-price="{{ $g->base_price }}"
                            {{ old('game_id', $discount->game_id ?? '') == $g->game_id ? 'selected' : '' }}>
                            {{ $g->title }}
                            (Rp {{ number_format($g->base_price, 0, ',', '.') }})
                        </option>
                        @endforeach
                    </select>
                    @error('game_id')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Persentase Diskon --}}
                <div>
                    <label class="adm-label">Persentase Diskon (%) <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <input type="number" name="discount_pct" id="discountPct"
                               min="1" max="100" step="1"
                               value="{{ old('discount_pct', $discount->discount_pct ?? '') }}"
                               placeholder="Misal: 20"
                               class="adm-input pr-10 @error('discount_pct') border-red-500 @enderror">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-bold">%</span>
                    </div>
                    @error('discount_pct')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror

                    {{-- Preview harga --}}
                    <div id="pricePreview" class="mt-2 hidden p-3 bg-[#0d0d0f] border border-[#1e1e22] rounded-xl">
                        <div class="flex items-center gap-3">
                            <div>
                                <p class="text-[11px] text-gray-500">Harga asli</p>
                                <p class="text-sm font-semibold text-gray-400 line-through" id="originalPrice">—</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <div>
                                <p class="text-[11px] text-gray-500">Harga setelah diskon</p>
                                <p class="text-sm font-bold text-emerald-400" id="finalPrice">—</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Periode --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="adm-label">Tanggal Mulai <span class="text-red-400">*</span></label>
                        <input type="datetime-local" name="start_date"
                               value="{{ old('start_date', isset($discount) ? \Carbon\Carbon::parse($discount->start_date)->format('Y-m-d\TH:i') : '') }}"
                               class="adm-input @error('start_date') border-red-500 @enderror">
                        @error('start_date')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="adm-label">Tanggal Berakhir <span class="text-red-400">*</span></label>
                        <input type="datetime-local" name="end_date"
                               value="{{ old('end_date', isset($discount) ? \Carbon\Carbon::parse($discount->end_date)->format('Y-m-d\TH:i') : '') }}"
                               class="adm-input @error('end_date') border-red-500 @enderror">
                        @error('end_date')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Kanan --}}
    <div class="space-y-5">
        {{-- Status --}}
        <div class="panel">
            <h3 class="panel-section-title">Status</h3>
            <label class="flex items-center gap-3 cursor-pointer select-none mt-2">
                <div class="relative">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $discount->is_active ?? true) ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="w-10 h-6 bg-[#1e1e22] rounded-full peer-checked:bg-[#0078f2] transition-colors"></div>
                    <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                </div>
                <div>
                    <p class="text-sm font-medium text-white">Aktifkan Diskon</p>
                    <p class="text-[11px] text-gray-500">Diskon akan berlaku pada periode yang ditentukan</p>
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
                {{ $isEdit ? 'Simpan Perubahan' : 'Buat Diskon' }}
            </button>
            <a href="{{ route('admin.discounts.index') }}"
               class="w-full border border-[#2a2a30] hover:border-gray-500 text-gray-400 hover:text-white font-medium py-3 rounded-xl transition-all text-center text-sm">
                Batal
            </a>
        </div>

        @if($isEdit)
        <div class="panel border-red-900/30">
            <h3 class="text-sm font-semibold text-red-400 mb-3">Hapus Diskon</h3>
            <form method="POST" action="{{ route('admin.discounts.destroy', $discount->discount_id) }}"
                  onsubmit="return confirm('Hapus diskon ini secara permanen?')">
                @csrf @method('DELETE')
                <button type="submit"
                        class="w-full bg-red-900/20 hover:bg-red-900/40 border border-red-700/40 text-red-400 text-sm font-semibold py-2.5 rounded-xl transition-all">
                    Hapus Permanen
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
    select.adm-input option, input[type="datetime-local"].adm-input { color-scheme: dark; }
    select.adm-input option { background-color: #111114; }
</style>

@push('scripts')
<script>
(function() {
    const gameSelect  = document.getElementById('gameSelect');
    const discountPct = document.getElementById('discountPct');
    const preview     = document.getElementById('pricePreview');
    const origEl      = document.getElementById('originalPrice');
    const finalEl     = document.getElementById('finalPrice');

    function updatePreview() {
        const opt = gameSelect.options[gameSelect.selectedIndex];
        const price = parseFloat(opt?.dataset?.price ?? 0);
        const pct   = parseFloat(discountPct?.value ?? 0);
        if (price > 0 && pct > 0) {
            const final = price * (1 - pct / 100);
            origEl.textContent  = 'Rp ' + price.toLocaleString('id-ID');
            finalEl.textContent = 'Rp ' + Math.round(final).toLocaleString('id-ID');
            preview.classList.remove('hidden');
        } else {
            preview.classList.add('hidden');
        }
    }
    gameSelect?.addEventListener('change', updatePreview);
    discountPct?.addEventListener('input', updatePreview);
    updatePreview();
})();
</script>
@endpush
