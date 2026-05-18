<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    /**
     * Muestra un listado paginado de usuarios.
     */
    public function index(Request $request): View
    {
        $busqueda = trim((string) $request->query('q', ''));
        $usuarios = Usuario::query()
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($query) use ($busqueda) {
                    if (ctype_digit($busqueda)) {
                        $query->orWhere('id_usuario', (int) $busqueda);
                    }

                    $query->orWhere('nombre_usuario', 'like', "%{$busqueda}%")
                        ->orWhere('correo', 'like', "%{$busqueda}%");
                });
            })
            ->orderByDesc('id_usuario')
            ->paginate(15)
            ->withQueryString();

        return view('gees.usuarios.index', compact('usuarios'));
    }

    /**
     * Muestra el formulario de creación.
     */
    public function create(): View
    {
        return view('gees.usuarios.create');
    }

    /**
     * Guarda un nuevo usuario en la base de datos.
     */
    public function store(Request $request): RedirectResponse
    {
        $datosValidados = $request->validate([
            'nombre_usuario' => ['required', 'string', 'max:255'],
            'correo' => ['required', 'email', 'max:255', 'unique:usuarios,correo'],
            'contrasena' => ['required', 'string', 'min:6'],
        ]);

        $datosValidados['contrasena'] = Hash::make($datosValidados['contrasena']);

        Usuario::create($datosValidados);

        return redirect()->route('usuarios.index')->with('exito', 'Usuario creado correctamente.');
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit(Usuario $usuario): View
    {
        return view('gees.usuarios.edit', compact('usuario'));
    }

    /**
     * Actualiza un usuario existente.
     */
    public function update(Request $request, Usuario $usuario): RedirectResponse
    {
        $datosValidados = $request->validate([
            'nombre_usuario' => ['required', 'string', 'max:255'],
            'correo' => [
                'required',
                'email',
                'max:255',
                Rule::unique('usuarios', 'correo')->ignore($usuario->id_usuario, 'id_usuario'),
            ],
            'contrasena' => ['nullable', 'string', 'min:6'],
        ]);

        if (! empty($datosValidados['contrasena'])) {
            $datosValidados['contrasena'] = Hash::make($datosValidados['contrasena']);
        } else {
            unset($datosValidados['contrasena']);
        }

        $usuario->update($datosValidados);

        return redirect()->route('usuarios.index')->with('exito', 'Usuario actualizado correctamente.');
    }

    /**
     * Elimina un usuario del sistema.
     */
    public function destroy(Usuario $usuario): RedirectResponse
    {
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('exito', 'Usuario eliminado correctamente.');
    }
}
