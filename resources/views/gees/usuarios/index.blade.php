<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Usuarios</h2></x-slot>
    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @include('gees.partials.mensajes')
        <x-admin-filter
            placeholder="Buscar por ID, nombre o correo"
            create-route="usuarios.create"
            create-label="Nuevo usuario"
        />
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100"><tr><th class="p-2">ID</th><th class="p-2">Nombre</th><th class="p-2">Correo</th><th class="p-2">Acciones</th></tr></thead>
                <tbody>
                @forelse($usuarios as $usuario)
                    <tr class="border-t">
                        <td class="p-2">{{ $usuario->id_usuario }}</td><td class="p-2">{{ $usuario->nombre_usuario }}</td><td class="p-2">{{ $usuario->correo }}</td>
                        <td class="p-2">
                            <a class="text-blue-600" href="{{ route('usuarios.edit', $usuario) }}">Editar</a>
                            <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}" class="inline">@csrf @method('DELETE')<button class="text-red-600 ms-2" onclick="return confirm('¿Eliminar usuario?')">Eliminar</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-6 text-center text-gray-500">No hay usuarios que coincidan con la búsqueda.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $usuarios->links() }}</div>
    </div>
</x-app-layout>
