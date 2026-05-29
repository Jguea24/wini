@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-amber-800 text-sm font-semibold leading-5 text-stone-950 focus:outline-none focus:border-amber-900 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-stone-600 hover:text-stone-950 hover:border-amber-700 focus:outline-none focus:text-stone-950 focus:border-amber-700 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
