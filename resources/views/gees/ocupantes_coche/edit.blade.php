<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Editar Ocupante</h2></x-slot>
    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        @include('gees.partials.mensajes')

        <form method="POST" action="{{ route('ocupantes-coche.update',$ocupante) }}" class="space-y-3 bg-white p-6 rounded shadow">
            @csrf
            @method('PUT')
            <label class="block">Coche
                <select name="id_coche" class="w-full rounded border">
                    @foreach($coches as $coche)
                        <option value="{{ $coche->id_coche }}" @selected(old('id_coche',$ocupante->id_coche)==$coche->id_coche)>Coche #{{ $coche->id_coche }} - {{ $coche->nombre_conductor }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('id_coche')" class="mt-2" />
            </label>
            <div>
                <input name="nombre_ocupante" value="{{ old('nombre_ocupante',$ocupante->nombre_ocupante) }}" class="w-full rounded border" placeholder="Nombre ocupante">
                <x-input-error :messages="$errors->get('nombre_ocupante')" class="mt-2" />
            </div>
            <button class="rounded bg-blue-600 px-4 py-2 text-white">Actualizar</button>
        </form>
    </div>
</x-app-layout>
