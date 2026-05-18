<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Partido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PartidoController extends Controller
{
    /**
     * Lista partidos con equipo.
     */
    public function index(Request $request): View
    {
        $busqueda = trim((string) $request->query('q', ''));
        $partidos = Partido::query()
            ->with('equipo')
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($query) use ($busqueda) {
                    if (ctype_digit($busqueda)) {
                        $query->orWhere('id_partido', (int) $busqueda)
                            ->orWhere('id_equipo', (int) $busqueda);
                    }

                    $query->orWhere('equipo_rival', 'like', "%{$busqueda}%")
                        ->orWhere('fecha', 'like', "%{$busqueda}%")
                        ->orWhere('lugar', 'like', "%{$busqueda}%")
                        ->orWhere('hora_quedada', 'like', "%{$busqueda}%")
                        ->orWhere('hora_partido', 'like', "%{$busqueda}%")
                        ->orWhereHas('equipo', function ($query) use ($busqueda) {
                            $query->where('nombre_equipo', 'like', "%{$busqueda}%");
                        });
                });
            })
            ->orderByDesc('id_partido')
            ->paginate(15)
            ->withQueryString();

        return view('gees.partidos.index', compact('partidos'));
    }

    /**
     * Muestra formulario de creación.
     */
    public function create(): View
    {
        $equipos = Equipo::query()->orderBy('nombre_equipo')->get();

        return view('gees.partidos.create', compact('equipos'));
    }

    /**
     * Guarda un partido.
     */
    public function store(Request $request): RedirectResponse
    {
        $datosValidados = $request->validate([
            'id_equipo' => ['nullable', 'integer', 'exists:equipos,id_equipo'],
            'equipo_rival' => ['nullable', 'string', 'max:255'],
            'fecha' => ['nullable', 'date'],
            'hora_quedada' => ['nullable', 'date_format:H:i'],
            'hora_partido' => ['nullable', 'date_format:H:i'],
            'lugar' => ['nullable', 'string', 'max:255'],
        ]);

        Partido::create($datosValidados);

        return redirect()->route('partidos.index')->with('exito', 'Partido creado correctamente.');
    }

    /**
     * Muestra formulario de edición.
     */
    public function edit(Partido $partido): View
    {
        $equipos = Equipo::query()->orderBy('nombre_equipo')->get();

        return view('gees.partidos.edit', compact('partido', 'equipos'));
    }

    /**
     * Actualiza un partido.
     */
    public function update(Request $request, Partido $partido): RedirectResponse
    {
        $datosValidados = $request->validate([
            'id_equipo' => ['nullable', 'integer', 'exists:equipos,id_equipo'],
            'equipo_rival' => ['nullable', 'string', 'max:255'],
            'fecha' => ['nullable', 'date'],
            'hora_quedada' => ['nullable', 'date_format:H:i'],
            'hora_partido' => ['nullable', 'date_format:H:i'],
            'lugar' => ['nullable', 'string', 'max:255'],
        ]);

        $partido->update($datosValidados);

        return redirect()->route('partidos.index')->with('exito', 'Partido actualizado correctamente.');
    }

    /**
     * Elimina un partido.
     */
    public function destroy(Partido $partido): RedirectResponse
    {
        $partido->delete();

        return redirect()->route('partidos.index')->with('exito', 'Partido eliminado correctamente.');
    }

    /**
     * Guarda un partido desde la zona publica del equipo activo.
     */
    public function storePublic(Request $request): RedirectResponse
    {
        $equipo = $this->equipoActivoComoEntrenador($request);

        if (! $equipo) {
            return redirect()->route('public.callups')
                ->withErrors(['equipo' => 'Solo un entrenador del equipo activo puede gestionar las convocatorias.']);
        }

        $existeConvocatoria = Partido::query()
            ->where('id_equipo', $equipo->id_equipo)
            ->exists();

        if ($existeConvocatoria) {
            return redirect()->route('public.callups')
                ->withErrors(['equipo' => 'Este equipo ya tiene una convocatoria. Editala o elimina la actual antes de crear otra.']);
        }

        Partido::create([
            ...$this->validarPartido($request),
            'id_equipo' => $equipo->id_equipo,
        ]);

        return redirect()->route('public.callups')->with('exito', 'Partido guardado correctamente.');
    }

    /**
     * Actualiza un partido desde la zona publica del equipo activo.
     */
    public function updatePublic(Request $request, Partido $partido): RedirectResponse
    {
        $equipo = $this->equipoActivoComoEntrenador($request);
        $idPartido = (int) $request->input('id_partido', $partido->id_partido);
        $partidoActual = Partido::query()->where('id_partido', $idPartido)->first();

        if (! $equipo || ! $partidoActual || (int) $partidoActual->id_equipo !== (int) $equipo->id_equipo) {
            Log::warning('No se pudo autorizar la edicion de convocatoria.', [
                'route_partido_id' => $partido->id_partido ?? null,
                'request_partido_id' => $request->input('id_partido'),
                'equipo_id' => $equipo?->id_equipo,
                'partido_encontrado' => (bool) $partidoActual,
                'partido_equipo_id' => $partidoActual?->id_equipo,
            ]);

            return redirect()->route('public.callups')
                ->withErrors(['equipo' => 'No puedes editar ese partido.']);
        }

        $datosValidados = [
            ...$this->validarPartido($request),
            'id_equipo' => $equipo->id_equipo,
        ];

        $filasActualizadas = DB::table('partidos')
            ->where('id_partido', $partidoActual->id_partido)
            ->update($datosValidados);

        Log::info('Resultado de la edicion de convocatoria.', [
            'partido_id' => $partidoActual->id_partido,
            'equipo_id' => $equipo->id_equipo,
            'payload' => $datosValidados,
            'filas_actualizadas' => $filasActualizadas,
        ]);

        return redirect()->route('public.callups')->with('exito', 'Partido actualizado correctamente.');
    }

    /**
     * Elimina un partido desde la zona publica del equipo activo.
     */
    public function destroyPublic(Request $request, Partido $partido): RedirectResponse
    {
        $equipo = $this->equipoActivoComoEntrenador($request);
        $partidoActual = Partido::query()->find($partido->id_partido);

        if (! $equipo || ! $partidoActual || (int) $partidoActual->id_equipo !== (int) $equipo->id_equipo) {
            return redirect()->route('public.callups')
                ->withErrors(['equipo' => 'No puedes eliminar ese partido.']);
        }

        $partidoActual->delete();

        return redirect()->route('public.callups')->with('exito', 'Partido eliminado correctamente.');
    }

    /**
     * Reglas comunes de validacion para partidos.
     *
     * @return array<string, mixed>
     */
    private function validarPartido(Request $request): array
    {
        return $request->validate([
            'equipo_rival' => ['nullable', 'string', 'max:255'],
            'fecha' => ['nullable', 'date'],
            'hora_quedada' => ['nullable', 'date_format:H:i'],
            'hora_partido' => ['nullable', 'date_format:H:i'],
            'lugar' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
