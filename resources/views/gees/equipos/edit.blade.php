<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Editar Equipo</h2></x-slot>
    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        @include('gees.partials.mensajes')

        <form method="POST" action="{{ route('equipos.update',$equipo) }}" class="space-y-6 bg-white p-6 rounded shadow">
            @csrf
            @method('PUT')

            <div>
                <label for="nombre_equipo" class="mb-2 block text-sm font-medium text-gray-700">Nombre de equipo</label>
                <input id="nombre_equipo" name="nombre_equipo" value="{{ old('nombre_equipo',$equipo->nombre_equipo) }}" class="w-full rounded border-gray-300" required>
                <x-input-error :messages="$errors->get('nombre_equipo')" class="mt-2" />
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="deporte" class="mb-2 block text-sm font-medium text-gray-700">Deporte</label>
                    <select id="deporte" name="deporte" class="w-full rounded border-gray-300" required>
                        @foreach ($deportes as $deporte)
                            <option value="{{ $deporte }}" @selected(old('deporte',$equipo->deporte) === $deporte)>{{ $deporte }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('deporte')" class="mt-2" />
                </div>

                <div>
                    <label for="categoria" class="mb-2 block text-sm font-medium text-gray-700">Categoria</label>
                    <select id="categoria" name="categoria" class="w-full rounded border-gray-300" required>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria }}" @selected(old('categoria',$equipo->categoria) === $categoria)>{{ $categoria }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('categoria')" class="mt-2" />
                </div>
            </div>

            <div class="rounded border border-gray-200 bg-gray-50 p-4">
                <input type="hidden" name="tiene_multas" value="0">
                <label class="flex items-start gap-3">
                    <input type="checkbox" name="tiene_multas" value="1" @checked(old('tiene_multas',$equipo->tiene_multas) == '1') class="mt-1 rounded border-gray-300 text-blue-600">
                    <span>
                        <span class="block font-medium text-gray-900">Este equipo tendra multas</span>
                        <span class="mt-1 block text-sm text-gray-600">Los codigos se conservan y solo se generan si el equipo aun no los tenia.</span>
                    </span>
                </label>
                <x-input-error :messages="$errors->get('tiene_multas')" class="mt-2" />
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded border border-dashed border-gray-300 p-4 text-sm text-gray-700">
                    <p class="font-medium text-gray-900">Codigo entrenador</p>
                    <p class="mt-2">{{ $equipo->codigo_grupo_entrenador }}</p>
                </div>
                <div class="rounded border border-dashed border-gray-300 p-4 text-sm text-gray-700">
                    <p class="font-medium text-gray-900">Codigo jugador</p>
                    <p class="mt-2">{{ $equipo->codigo_grupo_jugador }}</p>
                </div>
                <div class="rounded border border-dashed border-gray-300 p-4 text-sm text-gray-700">
                    <p class="font-medium text-gray-900">Codigo familiar</p>
                    <p class="mt-2">{{ $equipo->codigo_grupo_familiar }}</p>
                </div>
            </div>

            <button class="rounded bg-blue-600 px-4 py-2 text-white">Actualizar</button>
        </form>
    </div>
</x-app-layout>
