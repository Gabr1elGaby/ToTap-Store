@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-blue-400 dark:border-blue-500 text-start text-base font-bold text-gray-900 dark:text-white bg-blue-50 dark:bg-blue-900/50 focus:outline-none transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-semibold text-gray-600 dark:text-white hover:text-gray-900 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-800 focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
