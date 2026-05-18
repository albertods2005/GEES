<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Editar Coche</h2></x-slot>
    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        @include('gees.partials.mensajes')

        <form method="POST" action="{{ route('coches.update',$coche) }}" class="space-y-3 bg-white p-6 rounded shadow">
            @csrf
            @method('PUT')
            <div>
                <input name="nombre_conductor" value="{{ old('nombre_conductor',$coche->nombre_conductor) }}" class="w-full rounded border" placeholder="Nombre del conductor">
                <x-input-error :messages="$errors->get('nombre_conductor')" class="mt-2" />
            </div>
            <div>
                <input name="numero_plazas" type="number" value="{{ old('numero_plazas',$coche->numero_plazas) }}" class="w-full rounded border" placeholder="Número de plazas">
                <x-input-error :messages="$errors->get('numero_plazas')" class="mt-2" />
            </div>
            <label class="block">Equipo
                <select name="id_equipo" class="w-full rounded border">
                    @foreach($equipos as $equipo)
                        <option value="{{ $equipo->id_equipo }}" @selected(old('id_equipo',$coche->id_equipo)==$equipo->id_equipo)>{{ $equipo->nombre_equipo }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('id_equipo')" class="mt-2" />
            </label>
            <button class="rounded bg-blue-600 px-4 py-2 text-white">Actualizar</button>
        </form>
    </div>
</x-app-layout>
