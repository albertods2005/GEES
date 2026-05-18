<?php

namespace App\Http\Controllers;

use App\Models\Coche;
use App\Models\Equipo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CocheController extends Controller
{
    /**
     * Lista coches con equipo y ocupantes.
     */
    public function index(): View
    {
        $coches = Coche::query()->with(['equipo', 'ocupantes'])->orderByDesc('id_coche')->paginate(15);

        return view('gees.coches.index', compact('coches'));
    }

    /**
     * Muestra formulario de creación.
     */
    public function create(): View
    {
        $equipos = Equipo::query()->orderBy('nombre_equipo')->get();

        return view('gees.coches.create', compact('equipos'));
    }

    /**
     * Guarda un coche.
     */
    public function store(Request $request): RedirectResponse
    {
        $datosValidados = $request->validate([
            'nombre_conductor' => ['required', 'string', 'max:255'],
            'numero_plazas' => ['required', 'integer', 'min:1'],
            'id_equipo' => ['required', 'integer', 'exists:equipos,id_equipo'],
        ]);

        Coche::create($datosValidados);

        return redirect()->route('coches.index')->with('exito', 'Coche creado correctamente.');
    }

    /**
     * Muestra formulario de edición.
     */
    public function edit(Coche $coche): View
    {
        $equipos = Equipo::query()->orderBy('nombre_equipo')->get();

        return view('gees.coches.edit', compact('coche', 'equipos'));
    }

    /**
     * Actualiza un coche.
     */
    public function update(Request $request, Coche $coche): RedirectResponse
    {
        $datosValidados = $request->validate([
            'nombre_conductor' => ['required', 'string', 'max:255'],
            'numero_plazas' => ['required', 'integer', 'min:1'],
            'id_equipo' => ['required', 'integer', 'exists:equipos,id_equipo'],
        ]);

        $coche->update($datosValidados);

        return redirect()->route('coches.index')->with('exito', 'Coche actualizado correctamente.');
    }

    /**
     * Elimina un coche.
     */
    public function destroy(Coche $coche): RedirectResponse
    {
        $coche->delete();

        return redirect()->route('coches.index')->with('exito', 'Coche eliminado correctamente.');
    }
}
