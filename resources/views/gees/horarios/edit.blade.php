<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Editar Horario</h2></x-slot>
    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        @include('gees.partials.mensajes')

        <form method="POST" action="{{ route('horarios.update',$horario) }}" class="space-y-3 bg-white p-6 rounded shadow">
            @csrf
            @method('PUT')
            <div>
                <input name="dia" value="{{ old('dia',$horario->dia) }}" class="w-full rounded border" placeholder="Día">
                <x-input-error :messages="$errors->get('dia')" class="mt-2" />
            </div>
            <div>
                <input type="time" name="hora_quedada" value="{{ old('hora_quedada',$horario->hora_quedada) }}" class="w-full rounded border">
                <x-input-error :messages="$errors->get('hora_quedada')" class="mt-2" />
            </div>
            <div>
                <input type="time" name="hora_entreno" value="{{ old('hora_entreno',$horario->hora_entreno) }}" class="w-full rounded border">
                <x-input-error :messages="$errors->get('hora_entreno')" class="mt-2" />
            </div>
            <div>
                <input name="lugar" value="{{ old('lugar',$horario->lugar) }}" class="w-full rounded border" placeholder="Lugar">
                <x-input-error :messages="$errors->get('lugar')" class="mt-2" />
            </div>
            <label class="block">Equipo
                <select name="id_equipo" class="w-full rounded border">
                    <option value="">Sin equipo</option>
                    @foreach($equipos as $equipo)
                        <option value="{{ $equipo->id_equipo }}" @selected(old('id_equipo',$horario->id_equipo)==$equipo->id_equipo)>{{ $equipo->nombre_equipo }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('id_equipo')" class="mt-2" />
            </label>
            <button class="rounded bg-blue-600 px-4 py-2 text-white">Actualizar</button>
        </form>
    </div>
</x-app-layout>
