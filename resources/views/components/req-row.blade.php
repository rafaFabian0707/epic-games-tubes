{{--
    Komponen: req-row
    Digunakan di: games/show.blade.php — tab System Requirements
    Props:
      $label  (string) — nama spesifikasi, contoh: "OS", "CPU", "RAM"
      $value  (string) — nilai spesifikasi dari model SystemRequirement
--}}
@props(['label', 'value'])

<div class="group flex flex-col gap-1.5
            bg-gray-900/60 border border-gray-800
            hover:border-gray-600 hover:bg-gray-900/80
            rounded-xl px-4 py-3
            transition-all duration-200 ease-in-out">

    {{-- Label --}}
    <dt class="text-[11px] font-semibold uppercase tracking-widest text-gray-500
               group-hover:text-gray-400 transition-colors duration-200">
        {{ $label }}
    </dt>

    {{-- Value --}}
    <dd class="text-sm text-gray-200 leading-snug font-medium
               group-hover:text-white transition-colors duration-200">
        {{ $value }}
    </dd>

</div>