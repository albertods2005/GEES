<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">GEES - Panel Principal</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-4 md:grid-cols-2">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Equipos creados</p>
                    <p class="mt-2 text-4xl font-bold text-gray-900">{{ $totalEquipos }}</p>
                    <a href="{{ route('equipos.index') }}" class="mt-4 inline-block text-sm font-semibold text-blue-600">Ver todos los equipos</a>
                </div>

                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <p class="text-sm font-semibold uppercase tracking-wide text-gray-500">Usuarios registrados</p>
                    <p class="mt-2 text-4xl font-bold text-gray-900">{{ $totalUsuarios }}</p>
                    <a href="{{ route('usuarios.index') }}" class="mt-4 inline-block text-sm font-semibold text-blue-600">Ver usuarios</a>
                </div>
            </div>

            <div class="mt-6 bg-white shadow-sm sm:rounded-lg">
                <div class="border-b border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900">Ultimos equipos de la web</h3>
                    <p class="mt-1 text-sm text-gray-600">Aqui aparecen los equipos creados desde la zona publica y desde el panel admin.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-3 text-left">ID</th>
                                <th class="p-3 text-left">Nombre</th>
                                <th class="p-3 text-left">Categoria</th>
                                <th class="p-3 text-left">Deporte</th>
                                <th class="p-3 text-left">Usuarios</th>
                                <th class="p-3 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ultimosEquipos as $equipo)
                                <tr class="border-t">
                                    <td class="p-3">{{ $equipo->id_equipo }}</td>
                                    <td class="p-3 font-semibold text-gray-900">{{ $equipo->nombre_equipo }}</td>
                                    <td class="p-3">{{ $equipo->categoria }}</td>
                                    <td class="p-3">{{ $equipo->deporte }}</td>
                                    <td class="p-3">{{ $equipo->usuarios_count }}</td>
                                    <td class="p-3">
                                        <a class="text-blue-600" href="{{ route('equipos.edit', $equipo) }}">Editar</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-gray-500">
                                        No hay equipos en la base de datos. Crea uno desde la web o ejecuta el seeder.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
