<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Multa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MultaController extends Controller
{
    /**
     * Lista multas con equipo.
     */
    public function index(Request $request): View
    {
        $busqueda = trim((string) $request->query('q', ''));
        $multas = Multa::query()
            ->with('equipo')
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($query) use ($busqueda) {
                    if (ctype_digit($busqueda)) {
                        $query->orWhere('id_multa', (int) $busqueda)
                            ->orWhere('id_equipo', (int) $busqueda);
                    }

                    $query->orWhere('nombre_jugador', 'like', "%{$busqueda}%")
                        ->orWhere('motivo', 'like', "%{$busqueda}%")
                        ->orWhere('monto', 'like', "%{$busqueda}%")
                        ->orWhere('fecha_asignacion', 'like', "%{$busqueda}%")
                        ->orWhereHas('equipo', function ($query) use ($busqueda) {
                            $query->where('nombre_equipo', 'like', "%{$busqueda}%");
                        });
                });
            })
            ->orderByDesc('id_multa')
            ->paginate(15)
            ->withQueryString();

        return view('gees.multas.index', compact('multas'));
    }

    /**
     * Muestra formulario de creación.
     */
    public function create(): View
    {
        $equipos = Equipo::query()->orderBy('nombre_equipo')->get();

        return view('gees.multas.create', compact('equipos'));
    }

    /**
     * Guarda una multa.
     */
    public function store(Request $request): RedirectResponse
    {
        $datosValidados = $request->validate([
            'id_equipo' => ['nullable', 'integer', 'exists:equipos,id_equipo'],
            'motivo' => ['nullable', 'string', 'max:255'],
            'monto' => ['required', 'numeric', 'min:0'],
            'pagada' => ['required', 'boolean'],
            'fecha_asignacion' => ['nullable', 'date'],
            'nombre_jugador' => ['nullable', 'string', 'max:255'],
        ]);

        Multa::create($datosValidados);

        return redirect()->route('multas.index')->with('exito', 'Multa creada correctamente.');
    }

    /**
     * Muestra formulario de edición.
     */
    public function edit(Multa $multa): View
    {
        $equipos = Equipo::query()->orderBy('nombre_equipo')->get();

        return view('gees.multas.edit', compact('multa', 'equipos'));
    }

    /**
     * Actualiza una multa.
     */
    public function update(Request $request, Multa $multa): RedirectResponse
    {
        $datosValidados = $request->validate([
            'id_equipo' => ['nullable', 'integer', 'exists:equipos,id_equipo'],
            'motivo' => ['nullable', 'string', 'max:255'],
            'monto' => ['required', 'numeric', 'min:0'],
            'pagada' => ['required', 'boolean'],
            'fecha_asignacion' => ['nullable', 'date'],
            'nombre_jugador' => ['nullable', 'string', 'max:255'],
        ]);

        $multa->update($datosValidados);

        return redirect()->route('multas.index')->with('exito', 'Multa actualizada correctamente.');
    }

    /**
     * Elimina una multa.
     */
    public function destroy(Multa $multa): RedirectResponse
    {
        $multa->delete();

        return redirect()->route('multas.index')->with('exito', 'Multa eliminada correctamente.');
    }

    /**
     * Guarda una multa desde la zona publica del equipo activo.
     */
    public function storePublic(Request $request): RedirectResponse
    {
        $equipo = $this->equipoActivoComoEntrenador($request);

        if (! $equipo) {
            return redirect()->route('public.fines')
                ->withErrors(['equipo' => 'Solo un entrenador del equipo activo puede gestionar las multas.']);
        }

        Multa::create([
            ...$this->validarMulta($request),
            'id_equipo' => $equipo->id_equipo,
        ]);

        return redirect()->route('public.fines')->with('exito', 'Multa guardada correctamente.');
    }

    /**
     * Actualiza una multa desde la zona publica del equipo activo.
     */
    public function updatePublic(Request $request, Multa $multa): RedirectResponse
    {
        $equipo = $this->equipoActivoComoEntrenador($request);

        if (! $equipo || (int) $multa->id_equipo !== (int) $equipo->id_equipo) {
            return redirect()->route('public.fines')
                ->withErrors(['equipo' => 'No puedes editar esa multa.']);
        }

        $multa->update([
            ...$this->validarMulta($request),
            'id_equipo' => $equipo->id_equipo,
        ]);

        return redirect()->route('public.fines')->with('exito', 'Multa actualizada correctamente.');
    }

    /**
     * Marca una multa como pagada desde la zona publica del equipo activo.
     */
    public function markAsPaidPublic(Request $request, Multa $multa): RedirectResponse
    {
        $equipo = $this->equipoActivoComoEntrenador($request);

        if (! $equipo || (int) $multa->id_equipo !== (int) $equipo->id_equipo) {
            return redirect()->route('public.fines')
                ->withErrors(['equipo' => 'No puedes modificar esa multa.']);
        }

        if (! $multa->pagada) {
            $multa->update(['pagada' => true]);
        }

        return redirect()->route('public.fines')->with('exito', 'La multa ha quedado marcada como pagada.');
    }

    /**
     * Elimina una multa desde la zona publica del equipo activo.
     */
    public function destroyPublic(Request $request, Multa $multa): RedirectResponse
    {
        $equipo = $this->equipoActivoComoEntrenador($request);

        if (! $equipo || (int) $multa->id_equipo !== (int) $equipo->id_equipo) {
            return redirect()->route('public.fines')
                ->withErrors(['equipo' => 'No puedes eliminar esa multa.']);
        }

        $multa->delete();

        return redirect()->route('public.fines')->with('exito', 'Multa eliminada correctamente.');
    }

    /**
     * Reglas comunes de validacion para multas.
     *
     * @return array<string, mixed>
     */
    private function validarMulta(Request $request): array
    {
        return $request->validate([
            'motivo' => ['nullable', 'string', 'max:255'],
            'monto' => ['required', 'numeric', 'min:0'],
            'pagada' => ['required', 'boolean'],
            'fecha_asignacion' => ['nullable', 'date'],
            'nombre_jugador' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
