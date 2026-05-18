@props([
    'placeholder' => 'Buscar por nombre, ID...',
    'createRoute' => null,
    'createLabel' => 'Nuevo',
])

<div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
    <form method="GET" action="{{ url()->current() }}" class="flex flex-1 flex-col gap-3 sm:flex-row">
        <input
            type="search"
            name="q"
            value="{{ request('q') }}"
            class="w-full rounded border"
            placeholder="{{ $placeholder }}"
        >
        <div class="flex gap-2">
            <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white">Buscar</button>
            @if (request()->filled('q'))
                <a href="{{ url()->current() }}" class="public-cta-secondary px-4 py-2">Limpiar</a>
            @endif
        </div>
    </form>

    @if ($createRoute)
        <a href="{{ route($createRoute) }}" class="inline-flex justify-center rounded bg-blue-600 px-4 py-2 text-white">{{ $createLabel }}</a>
    @endif
</div>
