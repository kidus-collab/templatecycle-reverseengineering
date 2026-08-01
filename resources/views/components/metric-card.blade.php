@props(['label', 'value', 'subtext'])

<div class="bg-white p-3 rounded-xl border border-gray-200/90 shadow-2xs">
    <div class="text-[9px] font-bold tracking-widest text-gray-400 uppercase">{{ $label }}</div>
    <div class="text-xl font-bold text-gray-900 mt-0.5">{{ $value }}</div>
    <div class="text-[11px] text-gray-500 mt-0.5">{{ $subtext }}</div>
</div>
