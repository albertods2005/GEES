@props(['messages'])

@if ($messages)
    <ul {{ $attributes->class(['space-y-1 rounded-xl border border-rose-300/80 bg-rose-50 px-3 py-2 text-sm font-semibold text-rose-800 shadow-sm shadow-rose-900/10']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
