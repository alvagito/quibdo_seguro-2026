<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Oferta;

class ComercioController extends Controller
{
    /**
     * Panel principal de comercio
     */
    public function index()
    {
        $usuario = Auth::user();
        return view('comercio.index', compact('usuario'));
    }

    /**
     * Listar y crear ofertas
     */
    public function ofertas()
    {
        $ofertas = Auth::user()->ofertasPublicadas()->get();
        return view('comercio.ofertas', compact('ofertas'));
    }

    /**
     * Guardar nueva oferta
     */
    public function storeOferta(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'puntos' => 'required|integer|min:1'
        ]);

        Oferta::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'puntos' => $request->puntos,
            'id_comercio' => Auth::id()
        ]);

        return redirect('/comercio/ofertas')->with('success', 'Oferta creada correctamente.');
    }

    /**
     * Eliminar oferta
     */
    public function deleteOferta($id)
    {
        $oferta = Oferta::findOrFail($id);

        if ($oferta->id_comercio == Auth::id()) {
            $oferta->delete();
            return redirect('/comercio/ofertas')->with('success', 'Oferta eliminada.');
        }

        return redirect('/comercio/ofertas')->with('error', 'No puedes eliminar esta oferta.');
    }

    /**
     * Estadísticas de comercio
     */
    public function estadisticas()
    {
        $comercioId = Auth::id();
        
        $totalOfertas = Auth::user()->ofertasPublicadas()->count();
        $canjeadas = \App\Models\Canje::where('id_comercio', $comercioId)
            ->where('estado', 'validado')
            ->count();
        $puntosRedimidos = \App\Models\Canje::where('id_comercio', $comercioId)
            ->where('estado', 'validado')
            ->sum('puntos_canjeados');

        $canjesPendientes = \App\Models\Canje::where('id_comercio', $comercioId)
            ->where('estado', 'pendiente')
            ->with(['usuario', 'oferta'])
            ->get();

        return view('comercio.estadisticas', compact('totalOfertas', 'canjeadas', 'puntosRedimidos', 'canjesPendientes'));
    }

    /**
     * Validar canje con código QR
     */
    public function validarCanje(Request $request, $codigo = null)
    {
        // Si viene del formulario, usar el código del request
        $codigoValidar = $codigo ?? $request->codigo;
        
        $canje = \App\Models\Canje::where('codigo_qr', $codigoValidar)
            ->where('id_comercio', Auth::id())
            ->where('estado', 'pendiente')
            ->first();

        if (!$canje) {
            return redirect('/comercio/estadisticas')->with('error', 'Código inválido o ya utilizado.');
        }

        $canje->estado = 'validado';
        $canje->fecha_validacion = now();
        $canje->save();

        return redirect('/comercio/estadisticas')->with('success', 'Canje validado correctamente para ' . $canje->usuario->nombre);
    }
}
