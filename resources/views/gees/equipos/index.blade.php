<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Equipos</h2></x-slot>
    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        @include('gees.partials.mensajes')
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">{{ request()->filled('q') ? 'Equipos encontrados' : 'Total de equipos' }}</p>
                <p class="mt-1 text-2xl font-bold text-gray-900">{{ $equipos->count() }}</p>
            </div>
        </div>

        <x-admin-filter
            placeholder="Buscar por ID, nombre, categoria, deporte o codigo"
            create-route="equipos.create"
            create-label="Nuevo equipo"
        />

        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">ID</th>
                        <th class="p-2 text-left">Nombre</th>
                        <th class="p-2 text-left">Categoría</th>
                        <th class="p-2 text-left">Deporte</th>
                        <th class="p-2 text-left">Usuarios</th>
                        <th class="p-2 text-left">Código entrenador</th>
                        <th class="p-2 text-left">Código jugador</th>
                        <th class="p-2 text-left">Código familiar</th>
                        <th class="p-2 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($equipos as $equipo)
                        <tr class="border-t">
                            <td class="p-2">{{ $equipo->id_equipo }}</td>
                            <td class="p-2 font-semibold text-gray-900">{{ $equipo->nombre_equipo }}</td>
                            <td class="p-2">{{ $equipo->categoria }}</td>
                            <td class="p-2">{{ $equipo->deporte }}</td>
                            <td class="p-2">{{ $equipo->usuarios_count }}</td>
                            <td class="p-2 font-mono text-xs">{{ $equipo->codigo_grupo_entrenador }}</td>
                            <td class="p-2 font-mono text-xs">{{ $equipo->codigo_grupo_jugador }}</td>
                            <td class="p-2 font-mono text-xs">{{ $equipo->codigo_grupo_familiar }}</td>
                            <td class="p-2 whitespace-nowrap">
                                <a class="text-blue-600" href="{{ route('equipos.edit',$equipo) }}">Editar</a>
                                <form method="POST" action="{{ route('equipos.destroy',$equipo) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 ms-2" onclick="return confirm('¿Eliminar equipo?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-6 text-center text-gray-500">Todavía no hay equipos creados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
