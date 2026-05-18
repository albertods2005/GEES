<?php

namespace App\Http\Controllers;

use App\Models\Coche;
use App\Models\OcupanteCoche;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OcupanteCocheController extends Controller
{
    /**
     * Lista ocupantes con coche asociado.
     */
    public function index(): View
    {
        $ocupantes = OcupanteCoche::query()->with('coche')->orderByDesc('id')->paginate(15);

        return view('gees.ocupantes_coche.index', compact('ocupantes'));
    }

    /**
     * Muestra formulario de creación.
     */
    public function create(): View
    {
        $coches = Coche::query()->orderByDesc('id_coche')->get();

        return view('gees.ocupantes_coche.create', compact('coches'));
    }

    /**
     * Guarda un ocupante de coche.
     */
    public function store(Request $request): RedirectResponse
    {
        $datosValidados = $request->validate([
            'id_coche' => ['required', 'integer', 'exists:coches,id_coche'],
            'nombre_ocupante' => ['required', 'string', 'max:255'],
        ]);

        OcupanteCoche::create($datosValidados);

        return redirect()->route('ocupantes-coche.index')->with('exito', 'Ocupante creado correctamente.');
    }

    /**
     * Muestra formulario de edición.
     */
    public function edit(OcupanteCoche $ocupante_coche): View
    {
        $coches = Coche::query()->orderByDesc('id_coche')->get();

        return view('gees.ocupantes_coche.edit', [
            'ocupante' => $ocupante_coche,
            'coches' => $coches,
        ]);
    }

    /**
     * Actualiza un ocupante.
     */
    public function update(Request $request, OcupanteCoche $ocupante_coche): RedirectResponse
    {
        $datosValidados = $request->validate([
            'id_coche' => ['required', 'integer', 'exists:coches,id_coche'],
            'nombre_ocupante' => ['required', 'string', 'max:255'],
        ]);

        $ocupante_coche->update($datosValidados);

        return redirect()->route('ocupantes-coche.index')->with('exito', 'Ocupante actualizado correctamente.');
    }

    /**
     * Elimina un ocupante.
     */
    public function destroy(OcupanteCoche $ocupante_coche): RedirectResponse
    {
        $ocupante_coche->delete();

        return redirect()->route('ocupantes-coche.index')->with('exito', 'Ocupante eliminado correctamente.');
    }
}
