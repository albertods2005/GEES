@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center whitespace-nowrap rounded-full bg-white/10 px-3 py-2 text-sm font-medium leading-5 text-white shadow-[0_0_0_1px_rgba(255,255,255,0.08)] transition duration-150 ease-in-out focus:outline-none'
            : 'inline-flex items-center whitespace-nowrap rounded-full px-3 py-2 text-sm font-medium leading-5 text-slate-300 transition duration-150 ease-in-out hover:bg-white/10 hover:text-white focus:outline-none';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
