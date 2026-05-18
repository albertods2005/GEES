<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Editar Relación Usuario/Equipo</h2></x-slot>
    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        @include('gees.partials.mensajes')

        <form method="POST" action="{{ route('usuarios-equipos.update',$relacion) }}" class="space-y-3 bg-white p-6 rounded shadow">
            @csrf
            @method('PUT')
            <label class="block">Usuario
                <select name="id_usuario" class="w-full rounded border">
                    @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id_usuario }}" @selected(old('id_usuario',$relacion->id_usuario)==$usuario->id_usuario)>{{ $usuario->nombre_usuario }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('id_usuario')" class="mt-2" />
            </label>
            <label class="block">Equipo
                <select name="id_equipo" class="w-full rounded border">
                    @foreach($equipos as $equipo)
                        <option value="{{ $equipo->id_equipo }}" @selected(old('id_equipo',$relacion->id_equipo)==$equipo->id_equipo)>{{ $equipo->nombre_equipo }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('id_equipo')" class="mt-2" />
            </label>
            <label class="block">Rol
                <select name="rol" class="w-full rounded border">
                    <option value="entrenador" @selected(old('rol',$relacion->rol)=='entrenador')>entrenador</option>
                    <option value="jugador" @selected(old('rol',$relacion->rol)=='jugador')>jugador</option>
                    <option value="familiar" @selected(old('rol',$relacion->rol)=='familiar')>familiar</option>
                </select>
                <x-input-error :messages="$errors->get('rol')" class="mt-2" />
            </label>
            <button class="rounded bg-blue-600 px-4 py-2 text-white">Actualizar</button>
        </form>
    </div>
</x-app-layout>
