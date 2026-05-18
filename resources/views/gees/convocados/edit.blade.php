<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Editar Convocado</h2></x-slot>
    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        @include('gees.partials.mensajes')

        <form method="POST" action="{{ route('convocados.update',$convocado) }}" class="space-y-3 bg-white p-6 rounded shadow">
            @csrf
            @method('PUT')
            <label class="block">Partido
                <select name="id_partido" class="w-full rounded border">
                    @foreach($partidos as $partido)
                        <option value="{{ $partido->id_partido }}" @selected(old('id_partido',$convocado->id_partido)==$partido->id_partido)>Partido #{{ $partido->id_partido }} - {{ $partido->equipo_rival }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('id_partido')" class="mt-2" />
            </label>
            <div>
                <input name="nombre_jugador" value="{{ old('nombre_jugador',$convocado->nombre_jugador) }}" class="w-full rounded border" placeholder="Nombre jugador">
                <x-input-error :messages="$errors->get('nombre_jugador')" class="mt-2" />
            </div>
            <div>
                <input name="dorsal" type="number" value="{{ old('dorsal',$convocado->dorsal) }}" class="w-full rounded border" placeholder="Dorsal">
                <x-input-error :messages="$errors->get('dorsal')" class="mt-2" />
            </div>
            <button class="rounded bg-blue-600 px-4 py-2 text-white">Actualizar</button>
        </form>
    </div>
</x-app-layout>
