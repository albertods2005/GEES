@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full rounded-2xl border border-blue-300/25 bg-blue-400/10 px-4 py-3 text-start text-base font-medium text-white transition duration-150 ease-in-out focus:outline-none'
            : 'block w-full rounded-2xl border border-transparent px-4 py-3 text-start text-base font-medium text-slate-300 transition duration-150 ease-in-out hover:border-white/10 hover:bg-white/10 hover:text-white focus:outline-none';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
