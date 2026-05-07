@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 pt-1 border-b-4 border-primary-600 text-sm font-bold leading-5 text-primary-700 focus:outline-none focus:border-primary-800 transition-all duration-200 ease-in-out uppercase tracking-wide'
            : 'inline-flex items-center px-3 pt-1 border-b-4 border-transparent text-sm font-semibold leading-5 text-gray-600 hover:text-primary-700 hover:border-primary-200 focus:outline-none focus:text-primary-700 focus:border-primary-200 transition-all duration-200 ease-in-out uppercase tracking-wide hover:bg-primary-50 rounded-t-lg';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
