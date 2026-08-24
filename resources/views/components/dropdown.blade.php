@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white dark:bg-gray-800'])

@php
$alignmentClasses = match ($align) {
    'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
    'top' => 'origin-top',
    default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};

$width = match ($width) {
    '48' => 'w-56',
    default => $width,
};
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open" onclick="const m = this.parentElement.querySelector('.dropdown-panel'); if(m) m.classList.toggle('hidden');" class="cursor-pointer">
        {{ $trigger }}
    </div>

    <div x-show="open"
         class="dropdown-panel absolute z-50 mt-2 {{ $width }} rounded-2xl shadow-2xl {{ $alignmentClasses }} transition-all"
         style="display: none;"
         @click="open = false; this.classList.add('hidden')">
        <div class="rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-2xl {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
