<x-app-layout>
<x-slot name="header"><h2 class="font-semibold text-xl text-gray-800">Usuarios / Equipos</h2></x-slot>
<div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">@include('gees.partials.mensajes')
<x-admin-filter placeholder="Buscar por ID, usuario, correo, equipo o rol" create-route="usuarios-equipos.create" create-label="Nueva relación" />
<div class="overflow-x-auto bg-white rounded shadow"><table class="min-w-full text-sm"><thead class="bg-gray-100"><tr><th class="p-2">ID</th><th class="p-2">Usuario</th><th class="p-2">Equipo</th><th class="p-2">Rol</th><th class="p-2">Acciones</th></tr></thead><tbody>@foreach($relaciones as $relacion)<tr class="border-t"><td class="p-2">{{ $relacion->id }}</td><td class="p-2">{{ $relacion->usuario?->nombre_usuario }}</td><td class="p-2">{{ $relacion->equipo?->nombre_equipo }}</td><td class="p-2">{{ $relacion->rol }}</td><td class="p-2"><a class="text-blue-600" href="{{ route('usuarios-equipos.edit',$relacion) }}">Editar</a><form method="POST" action="{{ route('usuarios-equipos.destroy',$relacion) }}" class="inline">@csrf @method('DELETE')<button class="text-red-600 ms-2" onclick="return confirm('¿Eliminar relación?')">Eliminar</button></form></td></tr>@endforeach</tbody></table></div>
<div class="mt-4">{{ $relaciones->links() }}</div>
</div></x-app-layout>
