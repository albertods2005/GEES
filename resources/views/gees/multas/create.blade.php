<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Crear Multa</h2></x-slot>
    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        @include('gees.partials.mensajes')

        <form method="POST" action="{{ route('multas.store') }}" class="space-y-3 bg-white p-6 rounded shadow">
            @csrf
            <label class="block">Equipo
                <select name="id_equipo" class="w-full rounded border">
                    <option value="">Sin equipo</option>
                    @foreach($equipos as $equipo)
                        <option value="{{ $equipo->id_equipo }}" @selected(old('id_equipo') == $equipo->id_equipo)>{{ $equipo->nombre_equipo }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('id_equipo')" class="mt-2" />
            </label>
            <div>
                <input name="nombre_jugador" value="{{ old('nombre_jugador') }}" class="w-full rounded border" placeholder="Nombre jugador">
                <x-input-error :messages="$errors->get('nombre_jugador')" class="mt-2" />
            </div>
            <div>
                <input name="motivo" value="{{ old('motivo') }}" class="w-full rounded border" placeholder="Motivo">
                <x-input-error :messages="$errors->get('motivo')" class="mt-2" />
            </div>
            <div>
                <input name="monto" type="number" step="0.01" value="{{ old('monto') }}" class="w-full rounded border" placeholder="Monto">
                <x-input-error :messages="$errors->get('monto')" class="mt-2" />
            </div>
            <div>
                <input type="date" name="fecha_asignacion" value="{{ old('fecha_asignacion') }}" class="w-full rounded border">
                <x-input-error :messages="$errors->get('fecha_asignacion')" class="mt-2" />
            </div>
            <label class="block">Pagada
                <select name="pagada" class="w-full rounded border">
                    <option value="0" @selected(old('pagada') == '0')>No</option>
                    <option value="1" @selected(old('pagada') == '1')>Sí</option>
                </select>
                <x-input-error :messages="$errors->get('pagada')" class="mt-2" />
            </label>
            <button class="rounded bg-blue-600 px-4 py-2 text-white">Guardar</button>
        </form>
    </div>
</x-app-layout>
