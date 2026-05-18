<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Crear Equipo</h2></x-slot>
    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        @include('gees.partials.mensajes')

        <form method="POST" action="{{ route('equipos.store') }}" class="space-y-6 bg-white p-6 rounded shadow">
            @csrf

            <div>
                <label for="nombre_equipo" class="mb-2 block text-sm font-medium text-gray-700">Introduce nombre de equipo</label>
                <input id="nombre_equipo" name="nombre_equipo" value="{{ old('nombre_equipo') }}" class="w-full rounded border-gray-300" placeholder="Ejemplo: Cadete B" required>
                <x-input-error :messages="$errors->get('nombre_equipo')" class="mt-2" />
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="deporte" class="mb-2 block text-sm font-medium text-gray-700">Deporte</label>
                    <select id="deporte" name="deporte" class="w-full rounded border-gray-300" required>
                        <option value="">Selecciona un deporte</option>
                        @foreach ($deportes as $deporte)
                            <option value="{{ $deporte }}" @selected(old('deporte') === $deporte)>{{ $deporte }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('deporte')" class="mt-2" />
                </div>

                <div>
                    <label for="categoria" class="mb-2 block text-sm font-medium text-gray-700">Categoria</label>
                    <select id="categoria" name="categoria" class="w-full rounded border-gray-300" required>
                        <option value="">Selecciona una categoria</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria }}" @selected(old('categoria') === $categoria)>{{ $categoria }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('categoria')" class="mt-2" />
                </div>
            </div>

            <div class="rounded border border-gray-200 bg-gray-50 p-4">
                <input type="hidden" name="tiene_multas" value="0">
                <label class="flex items-start gap-3">
                    <input type="checkbox" name="tiene_multas" value="1" @checked(old('tiene_multas') == '1') class="mt-1 rounded border-gray-300 text-blue-600">
                    <span>
                        <span class="block font-medium text-gray-900">Este equipo tendra multas</span>
                        <span class="mt-1 block text-sm text-gray-600">Los codigos de acceso para entrenador, jugador y familiar se generaran automaticamente al guardar.</span>
                    </span>
                </label>
                <x-input-error :messages="$errors->get('tiene_multas')" class="mt-2" />
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded border border-dashed border-gray-300 p-4 text-sm text-gray-600">Codigo de entrenador: se genera automaticamente.</div>
                <div class="rounded border border-dashed border-gray-300 p-4 text-sm text-gray-600">Codigo de jugador: se genera automaticamente.</div>
                <div class="rounded border border-dashed border-gray-300 p-4 text-sm text-gray-600">Codigo de familiar: se genera automaticamente.</div>
            </div>

            <button class="rounded bg-blue-600 px-4 py-2 text-white">Guardar</button>
        </form>
    </div>
</x-app-layout>
