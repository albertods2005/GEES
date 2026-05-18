<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Editar Usuario</h2></x-slot>
    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        @include('gees.partials.mensajes')
        <form method="POST" action="{{ route('usuarios.update', $usuario) }}" class="space-y-4 bg-white p-6 rounded shadow">@csrf @method('PUT')
            <div>
                <input name="nombre_usuario" value="{{ old('nombre_usuario', $usuario->nombre_usuario) }}" class="w-full rounded border" placeholder="Nombre">
                <x-input-error :messages="$errors->get('nombre_usuario')" class="mt-2" />
            </div>
            <div>
                <input name="correo" type="email" value="{{ old('correo', $usuario->correo) }}" class="w-full rounded border" placeholder="Correo">
                <x-input-error :messages="$errors->get('correo')" class="mt-2" />
            </div>
            <div>
                <x-password-input name="contrasena" class="w-full rounded border" placeholder="Nueva contraseña (opcional)" />
                <x-input-error :messages="$errors->get('contrasena')" class="mt-2" />
            </div>
            <button class="rounded bg-blue-600 px-4 py-2 text-white">Actualizar</button>
            <a href="{{ route('usuarios.index') }}" class="ms-2">Cancelar</a>
        </form>
    </div>
</x-app-layout>
