<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Usuario;
use App\Models\UsuarioEquipo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UsuarioEquipoController extends Controller
{
    /**
     * Lista relaciones usuario-equipo con datos vinculados.
     */
    public function index(Request $request): View
    {
        $busqueda = trim((string) $request->query('q', ''));
        $relaciones = UsuarioEquipo::query()
            ->with(['usuario', 'equipo'])
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($query) use ($busqueda) {
                    if (ctype_digit($busqueda)) {
                        $query->orWhere('id', (int) $busqueda)
                            ->orWhere('id_usuario', (int) $busqueda)
                            ->orWhere('id_equipo', (int) $busqueda);
                    }

                    $query->orWhere('rol', 'like', "%{$busqueda}%")
                        ->orWhereHas('usuario', function ($query) use ($busqueda) {
                            $query->where('nombre_usuario', 'like', "%{$busqueda}%")
                                ->orWhere('correo', 'like', "%{$busqueda}%");
                        })
                        ->orWhereHas('equipo', function ($query) use ($busqueda) {
                            $query->where('nombre_equipo', 'like', "%{$busqueda}%")
                                ->orWhere('categoria', 'like', "%{$busqueda}%")
                                ->orWhere('deporte', 'like', "%{$busqueda}%");
                        });
                });
            })
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('gees.usuarios_equipos.index', compact('relaciones'));
    }

    /**
     * Muestra formulario de creación de relación.
     */
    public function create(): View
    {
        $usuarios = Usuario::query()->orderBy('nombre_usuario')->get();
        $equipos = Equipo::query()->orderBy('nombre_equipo')->get();

        return view('gees.usuarios_equipos.create', compact('usuarios', 'equipos'));
    }

    /**
     * Guarda una nueva relación usuario-equipo.
     */
    public function store(Request $request): RedirectResponse
    {
        $datosValidados = $request->validate([
            'id_usuario' => ['required', 'integer', 'exists:usuarios,id_usuario'],
            'id_equipo' => ['required', 'integer', 'exists:equipos,id_equipo'],
            'rol' => ['required', 'string', 'in:entrenador,jugador,familiar'],
        ]);

        $existe = UsuarioEquipo::query()
            ->where('id_usuario', $datosValidados['id_usuario'])
            ->where('id_equipo', $datosValidados['id_equipo'])
            ->exists();

        if ($existe) {
            return back()
                ->withErrors(['id_usuario' => 'Este usuario ya está asignado a ese equipo.'])
                ->withInput();
        }

        UsuarioEquipo::create($datosValidados);

        return redirect()->route('usuarios-equipos.index')->with('exito', 'Relación creada correctamente.');
    }

    /**
     * Muestra formulario de edición de relación.
     */
    public function edit(UsuarioEquipo $usuarios_equipo): View
    {
        $usuarios = Usuario::query()->orderBy('nombre_usuario')->get();
        $equipos = Equipo::query()->orderBy('nombre_equipo')->get();

        return view('gees.usuarios_equipos.edit', [
            'relacion' => $usuarios_equipo,
            'usuarios' => $usuarios,
            'equipos' => $equipos,
        ]);
    }

    /**
     * Actualiza una relación usuario-equipo.
     */
    public function update(Request $request, UsuarioEquipo $usuarios_equipo): RedirectResponse
    {
        $datosValidados = $request->validate([
            'id_usuario' => ['required', 'integer', 'exists:usuarios,id_usuario'],
            'id_equipo' => ['required', 'integer', 'exists:equipos,id_equipo'],
            'rol' => ['required', 'string', 'in:entrenador,jugador,familiar'],
        ]);

        $existe = UsuarioEquipo::query()
            ->where('id_usuario', $datosValidados['id_usuario'])
            ->where('id_equipo', $datosValidados['id_equipo'])
            ->where('id', '!=', $usuarios_equipo->id)
            ->exists();

        if ($existe) {
            return back()
                ->withErrors(['id_usuario' => 'Ya existe otra relación con ese usuario y equipo.'])
                ->withInput();
        }

        $usuarios_equipo->update($datosValidados);

        return redirect()->route('usuarios-equipos.index')->with('exito', 'Relación actualizada correctamente.');
    }

    /**
     * Elimina una relación usuario-equipo.
     */
    public function destroy(UsuarioEquipo $usuarios_equipo): RedirectResponse
    {
        $usuarios_equipo->delete();

        return redirect()->route('usuarios-equipos.index')->with('exito', 'Relación eliminada correctamente.');
    }
}
