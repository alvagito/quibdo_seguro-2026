<?php

namespace App\Http\Controllers;

use App\Services\IncidentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function __construct(private IncidentService $incidentService) {}

    /**
     * GET /api/incidentes
     * Filtros opcionales: tipo, periodo
     */
    public function index(Request $request): JsonResponse
    {
        $incidentes = $this->incidentService->listar($request->only(['tipo', 'periodo']));

        return response()->json([
            'success' => true,
            'message' => 'Incidentes obtenidos.',
            'data'    => $incidentes,
            'total'   => count($incidentes),
        ]);
    }

    /**
     * GET /api/incidentes/{id}
     */
    public function show(string $id): JsonResponse
    {
        $incidente = $this->incidentService->obtener($id);

        if (!$incidente) {
            return response()->json([
                'success' => false,
                'message' => 'Incidente no encontrado.',
                'data'    => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Incidente encontrado.',
            'data'    => $incidente,
        ]);
    }

    /**
     * POST /api/incidentes
     * Requiere: auth
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'id_tipo_incidente'    => 'required|integer|in:1,2,3,4',
            'descripcion'          => 'required|string|max:500',
            'direccion_aproximada' => 'required|string|max:255',
            'latitud'              => 'required|numeric',
            'longitud'             => 'required|numeric',
            'evidencia_foto'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $authUser = $request->attributes->get('auth_user');

        $result = $this->incidentService->crear(
            array_merge($request->except('evidencia_foto'), [
                'id_usuario' => $authUser['id'],
            ]),
            $request->file('evidencia_foto')
        );

        $status = $result['success'] ? 201 : 409;

        return response()->json($result, $status);
    }

    /**
     * POST /api/incidentes/comentarios
     * Requiere: auth
     */
    public function comentar(Request $request): JsonResponse
    {
        $request->validate([
            'id_incidente' => 'required|string',
            'comentario'   => 'required|string|max:500',
        ]);

        $authUser = $request->attributes->get('auth_user');

        $result = $this->incidentService->agregarComentario(
            $request->id_incidente,
            $authUser['id'],
            $request->comentario,
            $authUser['rol'] === 'autoridad'
        );

        $status = $result['success'] ? 201 : 404;

        return response()->json($result, $status);
    }
}
