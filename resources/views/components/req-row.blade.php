{{-- req-row: label atas (abu kecil), value bawah (putih bold) — Epic Games style --}}
@props(['label', 'value'])

<div class="py-3 border-b border-gray-800/60 last:border-b-0">
    <dt class="text-xs text-gray-500 mb-1 leading-none">{{ $label }}</dt>
    <dd class="text-sm font-semibold text-white leading-snug">{{ $value }}</dd>
</div>
