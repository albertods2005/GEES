<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Horario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HorarioController extends Controller
{
    /**
     * Lista horarios con equipo.
     */
    public function index(Request $request): View
    {
        $busqueda = trim((string) $request->query('q', ''));
        $horarios = Horario::query()
            ->with('equipo')
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($query) use ($busqueda) {
                    if (ctype_digit($busqueda)) {
                        $query->orWhere('id', (int) $busqueda)
                            ->orWhere('id_equipo', (int) $busqueda);
                    }

                    $query->orWhere('dia', 'like', "%{$busqueda}%")
                        ->orWhere('lugar', 'like', "%{$busqueda}%")
                        ->orWhere('hora_quedada', 'like', "%{$busqueda}%")
                        ->orWhere('hora_entreno', 'like', "%{$busqueda}%")
                        ->orWhereHas('equipo', function ($query) use ($busqueda) {
                            $query->where('nombre_equipo', 'like', "%{$busqueda}%");
                        });
                });
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('gees.horarios.index', compact('horarios'));
    }

    /**
     * Muestra formulario de creación.
     */
    public function create(): View
    {
        $equipos = Equipo::query()->orderBy('nombre_equipo')->get();

        return view('gees.horarios.create', compact('equipos'));
    }

    /**
     * Guarda un horario.
     */
    public function store(Request $request): RedirectResponse
    {
        $datosValidados = $request->validate([
            'dia' => ['nullable', 'date'],
            'hora_quedada' => ['nullable', 'date_format:H:i'],
            'hora_entreno' => ['nullable', 'date_format:H:i'],
            'lugar' => ['nullable', 'string', 'max:255'],
            'id_equipo' => ['nullable', 'integer', 'exists:equipos,id_equipo'],
        ]);

        Horario::create($datosValidados);

        return redirect()->route('horarios.index')->with('exito', 'Horario creado correctamente.');
    }

    /**
     * Muestra formulario de edición.
     */
    public function edit(Horario $horario): View
    {
        $equipos = Equipo::query()->orderBy('nombre_equipo')->get();

        return view('gees.horarios.edit', compact('horario', 'equipos'));
    }

    /**
     * Actualiza un horario.
     */
    public function update(Request $request, Horario $horario): RedirectResponse
    {
        $datosValidados = $request->validate([
            'dia' => ['nullable', 'date'],
            'hora_quedada' => ['nullable', 'date_format:H:i'],
            'hora_entreno' => ['nullable', 'date_format:H:i'],
            'lugar' => ['nullable', 'string', 'max:255'],
            'id_equipo' => ['nullable', 'integer', 'exists:equipos,id_equipo'],
        ]);

        $horario->update($datosValidados);

        return redirect()->route('horarios.index')->with('exito', 'Horario actualizado correctamente.');
    }

    /**
     * Elimina un horario.
     */
    public function destroy(Horario $horario): RedirectResponse
    {
        $horario->delete();

        return redirect()->route('horarios.index')->with('exito', 'Horario eliminado correctamente.');
    }

    /**
     * Guarda un horario desde la zona publica del equipo activo.
     */
    public function storePublic(Request $request): RedirectResponse
    {
        $equipo = $this->equipoActivoComoEntrenador($request);

        if (! $equipo) {
            return redirect()->route('public.trainings')
                ->withErrors(['equipo' => 'Solo un entrenador del equipo activo puede gestionar los entrenamientos.']);
        }

        Horario::create([
            ...$this->validarHorario($request),
            'id_equipo' => $equipo->id_equipo,
        ]);

        return redirect()->route('public.trainings')->with('exito', 'Entrenamiento guardado correctamente.');
    }

    /**
     * Actualiza un horario desde la zona publica del equipo activo.
     */
    public function updatePublic(Request $request, Horario $horario): RedirectResponse
    {
        $equipo = $this->equipoActivoComoEntrenador($request);

        if (! $equipo || (int) $horario->id_equipo !== (int) $equipo->id_equipo) {
            return redirect()->route('public.trainings')
                ->withErrors(['equipo' => 'No puedes editar ese entrenamiento.']);
        }

        $horario->update([
            ...$this->validarHorario($request),
            'id_equipo' => $equipo->id_equipo,
        ]);

        return redirect()->route('public.trainings')->with('exito', 'Entrenamiento actualizado correctamente.');
    }

    /**
     * Elimina un horario desde la zona publica del equipo activo.
     */
    public function destroyPublic(Request $request, Horario $horario): RedirectResponse
    {
        $equipo = $this->equipoActivoComoEntrenador($request);

        if (! $equipo || (int) $horario->id_equipo !== (int) $equipo->id_equipo) {
            return redirect()->route('public.trainings')
                ->withErrors(['equipo' => 'No puedes eliminar ese entrenamiento.']);
        }

        $horario->delete();

        return redirect()->route('public.trainings')->with('exito', 'Entrenamiento eliminado correctamente.');
    }

    /**
     * Reglas comunes de validacion para horarios.
     *
     * @return array<string, mixed>
     */
    private function validarHorario(Request $request): array
    {
        return $request->validate([
            'dia' => ['nullable', 'date'],
            'hora_quedada' => ['nullable', 'date_format:H:i'],
            'hora_entreno' => ['nullable', 'date_format:H:i'],
            'lugar' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
