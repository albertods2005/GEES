<?php

namespace App\Http\Controllers;

use App\Models\Convocado;
use App\Models\Partido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConvocadoController extends Controller
{
    /**
     * Lista convocados con partido asociado.
     */
    public function index(Request $request): View
    {
        $busqueda = trim((string) $request->query('q', ''));
        $convocados = Convocado::query()
            ->with('partido.equipo')
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($query) use ($busqueda) {
                    if (ctype_digit($busqueda)) {
                        $query->orWhere('id', (int) $busqueda)
                            ->orWhere('id_partido', (int) $busqueda)
                            ->orWhere('dorsal', (int) $busqueda);
                    }

                    $query->orWhere('nombre_jugador', 'like', "%{$busqueda}%")
                        ->orWhereHas('partido', function ($query) use ($busqueda) {
                            $query->where('equipo_rival', 'like', "%{$busqueda}%")
                                ->orWhereHas('equipo', function ($query) use ($busqueda) {
                                    $query->where('nombre_equipo', 'like', "%{$busqueda}%");
                                });
                        });
                });
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('gees.convocados.index', compact('convocados'));
    }

    /**
     * Muestra formulario de creación.
     */
    public function create(): View
    {
        $partidos = Partido::query()->orderByDesc('id_partido')->get();

        return view('gees.convocados.create', compact('partidos'));
    }

    /**
     * Guarda un convocado.
     */
    public function store(Request $request): RedirectResponse
    {
        $datosValidados = $request->validate([
            'id_partido' => ['required', 'integer', 'exists:partidos,id_partido'],
            'nombre_jugador' => ['nullable', 'string', 'max:255'],
            'dorsal' => ['required', 'integer', 'min:1'],
        ]);

        Convocado::create($datosValidados);

        return redirect()->route('convocados.index')->with('exito', 'Convocado creado correctamente.');
    }

    /**
     * Muestra formulario de edición.
     */
    public function edit(Convocado $convocado): View
    {
        $partidos = Partido::query()->orderByDesc('id_partido')->get();

        return view('gees.convocados.edit', compact('convocado', 'partidos'));
    }

    /**
     * Actualiza un convocado.
     */
    public function update(Request $request, Convocado $convocado): RedirectResponse
    {
        $datosValidados = $request->validate([
            'id_partido' => ['required', 'integer', 'exists:partidos,id_partido'],
            'nombre_jugador' => ['nullable', 'string', 'max:255'],
            'dorsal' => ['required', 'integer', 'min:1'],
        ]);

        $convocado->update($datosValidados);

        return redirect()->route('convocados.index')->with('exito', 'Convocado actualizado correctamente.');
    }

    /**
     * Elimina un convocado.
     */
    public function destroy(Convocado $convocado): RedirectResponse
    {
        $convocado->delete();

        return redirect()->route('convocados.index')->with('exito', 'Convocado eliminado correctamente.');
    }

    /**
     * Guarda un convocado desde la zona publica del equipo activo.
     */
    public function storePublic(Request $request, Partido $partido): RedirectResponse
    {
        $equipo = $this->equipoActivoComoEntrenador($request);

        if (! $equipo || (int) $partido->id_equipo !== (int) $equipo->id_equipo) {
            return redirect()->route('public.callups')
                ->withErrors(['equipo' => 'No puedes añadir convocados a ese partido.']);
        }

        Convocado::create([
            ...$this->validarConvocado($request),
            'id_partido' => $partido->id_partido,
        ]);

        return redirect()->route('public.callups')->with('exito', 'Convocado guardado correctamente.');
    }

    /**
     * Actualiza un convocado desde la zona publica del equipo activo.
     */
    public function updatePublic(Request $request, Convocado $convocado): RedirectResponse
    {
        $equipo = $this->equipoActivoComoEntrenador($request);
        $partido = $convocado->partido;

        if (! $equipo || ! $partido || (int) $partido->id_equipo !== (int) $equipo->id_equipo) {
            return redirect()->route('public.callups')
                ->withErrors(['equipo' => 'No puedes editar ese convocado.']);
        }

        $convocado->update([
            ...$this->validarConvocado($request),
            'id_partido' => $partido->id_partido,
        ]);

        return redirect()->route('public.callups')->with('exito', 'Convocado actualizado correctamente.');
    }

    /**
     * Elimina un convocado desde la zona publica del equipo activo.
     */
    public function destroyPublic(Request $request, Convocado $convocado): RedirectResponse
    {
        $equipo = $this->equipoActivoComoEntrenador($request);
        $partido = $convocado->partido;

        if (! $equipo || ! $partido || (int) $partido->id_equipo !== (int) $equipo->id_equipo) {
            return redirect()->route('public.callups')
                ->withErrors(['equipo' => 'No puedes eliminar ese convocado.']);
        }

        $convocado->delete();

        return redirect()->route('public.callups')->with('exito', 'Convocado eliminado correctamente.');
    }

    /**
     * Reglas comunes de validacion para convocados.
     *
     * @return array<string, mixed>
     */
    private function validarConvocado(Request $request): array
    {
        return $request->validate([
            'nombre_jugador' => ['nullable', 'string', 'max:255'],
            'dorsal' => ['required', 'integer', 'min:1'],
        ]);
    }
}
