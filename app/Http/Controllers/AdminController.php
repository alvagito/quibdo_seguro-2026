<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Incidente;
use App\Models\Oferta;
use App\Models\Recompensa;

class AdminController extends Controller
{
    /**
     * Panel principal de admin
     */
    public function index()
    {
        return view('admin.index');
    }

    /**
     * Listar usuarios con búsqueda y paginación
     */
    public function usuarios(Request $request)
    {
        $query = User::query();

        // Búsqueda
        if ($request->has('buscar') && $request->buscar != '') {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('email', 'like', "%{$buscar}%");
            });
        }

        // Filtro por rol
        if ($request->has('rol') && $request->rol != '') {
            $query->where('rol', $request->rol);
        }

        $usuarios = $query->paginate(15);
        return view('admin.usuarios', compact('usuarios'));
    }

    /**
     * Mostrar formulario de edición de usuario
     */
    public function editUsuario($id)
    {
        $usuario = User::findOrFail($id);
        return view('admin.edit-usuario', compact('usuario'));
    }

    /**
     * Actualizar usuario
     */
    public function updateUsuario(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id . ',_id',
            'rol' => 'required|in:normal,admin,autoridad,comercio',
            'puntos' => 'required|integer|min:0',
        ]);

        $usuario->nombre = $request->nombre;
        $usuario->email = $request->email;
        $usuario->rol = $request->rol;
        $usuario->puntos = $request->puntos;

        // Solo actualizar contraseña si se proporciona una nueva
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:6|confirmed',
            ]);
            $usuario->password = \Hash::make($request->password);
        }

        $usuario->save();

        return redirect('/admin/usuarios')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Eliminar usuario
     */
    public function deleteUsuario($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect('/admin/usuarios')->with('success', 'Usuario eliminado correctamente.');
    }

    /**
     * Listar reportes con búsqueda y filtros
     */
    public function reportes(Request $request)
    {
        $query = Incidente::with('usuario');

        // Búsqueda por descripción o dirección
        if ($request->has('buscar') && $request->buscar != '') {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('descripcion', 'like', "%{$buscar}%")
                  ->orWhere('direccion_aproximada', 'like', "%{$buscar}%");
            });
        }

        // Filtro por tipo
        if ($request->has('tipo') && $request->tipo != '') {
            $query->where('id_tipo_incidente', (int)$request->tipo);
        }



        $reportes = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Mantener parámetros de filtro en la paginación
        $reportes->appends($request->query());
        
        return view('admin.reportes', compact('reportes'));
    }

    /**
     * Eliminar reporte
     */
    public function deleteReporte($id)
    {
        $reporte = Incidente::findOrFail($id);
        $reporte->delete();

        return redirect('/admin/reportes')->with('success', 'Reporte eliminado correctamente.');
    }

    /**
     * Listar comercios
     */
    public function comercios()
    {
        $comercios = User::where('rol', 'comercio')->with('ofertasPublicadas')->get();
        return view('admin.comercios', compact('comercios'));
    }

    /**
     * Listar ofertas de comercios para gestión
     */
    public function ofertas()
    {
        $ofertas = Oferta::with('comercio')->orderBy('created_at', 'desc')->get();
        return view('admin.ofertas', compact('ofertas'));
    }

    /**
     * Mostrar formulario de edición de oferta
     */
    public function editOferta($id)
    {
        $oferta = Oferta::with('comercio')->findOrFail($id);
        return view('admin.edit-oferta', compact('oferta'));
    }

    /**
     * Actualizar oferta
     */
    public function updateOferta(Request $request, $id)
    {
        $oferta = Oferta::findOrFail($id);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'puntos' => 'required|integer|min:1'
        ]);

        $oferta->titulo = $request->titulo;
        $oferta->descripcion = $request->descripcion;
        $oferta->puntos = $request->puntos;
        $oferta->save();

        return redirect('/admin/ofertas')->with('success', 'Oferta actualizada correctamente.');
    }

    /**
     * Eliminar oferta
     */
    public function deleteOferta($id)
    {
        $oferta = Oferta::findOrFail($id);
        $oferta->delete();

        return redirect('/admin/ofertas')->with('success', 'Oferta eliminada correctamente.');
    }

    /**
     * Gestionar recompensas del sistema (mantener funcionalidad original)
     */
    public function recompensas()
    {
        $recompensas = Recompensa::all();
        return view('admin.recompensas', compact('recompensas'));
    }

    /**
     * Crear recompensa del sistema
     */
    public function storeRecompensa(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'puntos' => 'required|integer|min:1'
        ]);

        Recompensa::create([
            'nombre' => $request->nombre,
            'puntos' => $request->puntos
        ]);

        return redirect('/admin/recompensas')->with('success', 'Recompensa creada correctamente.');
    }

    /**
     * Eliminar recompensa del sistema
     */
    public function deleteRecompensa($id)
    {
        $recompensa = Recompensa::findOrFail($id);
        $recompensa->delete();

        return redirect('/admin/recompensas')->with('success', 'Recompensa eliminada correctamente.');
    }
}
