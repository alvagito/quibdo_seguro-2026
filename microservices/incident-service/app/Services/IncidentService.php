<?php

namespace App\Services;

use App\Interfaces\NotificationInterface;
use App\Models\Comentario;
use App\Models\Incidente;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class IncidentService
{
    public function __construct(private NotificationInterface $notificationService) {}

    /**
     * Listar incidentes con filtros opcionales.
     */
    public function listar(array $filtros = []): array
    {
        $query = Incidente::query();

        if (!empty($filtros['tipo'])) {
            $query->where('id_tipo_incidente', (int) $filtros['tipo']);
        }

        if (!empty($filtros['periodo'])) {
            $query->where('created_at', '>=', match ($filtros['periodo']) {
                '24h' => Carbon::now()->subHours(24),
                '7d'  => Carbon::now()->subDays(7),
                '30d' => Carbon::now()->subDays(30),
                default => Carbon::now()->subDays(365),
            });
        }

        $incidentes = $query->orderBy('created_at', 'desc')->get();

        return $incidentes->map(fn($i) => $this->formatIncidente($i))->toArray();
    }

    /**
     * Obtener un incidente por ID.
     */
    public function obtener(string $id): ?array
    {
        $incidente = Incidente::with('comentarios')->find($id);

        return $incidente ? $this->formatIncidente($incidente, true) : null;
    }

    /**
     * Crear nuevo incidente con validación de duplicados.
     */
    public function crear(array $data, ?UploadedFile $foto = null): array
    {
        // Validar duplicados en radio de ~100m (0.001 grados ≈ 111m)
        $duplicado = Incidente::whereBetween('latitud', [
                (float)$data['latitud'] - 0.001,
                (float)$data['latitud'] + 0.001,
            ])
            ->whereBetween('longitud', [
                (float)$data['longitud'] - 0.001,
                (float)$data['longitud'] + 0.001,
            ])
            ->where('id_tipo_incidente', (int)$data['id_tipo_incidente'])
            ->where('created_at', '>=', Carbon::now()->subHours(24))
            ->first();

        if ($duplicado) {
            return [
                'success'  => false,
                'message'  => 'Ya existe un reporte similar en esta ubicación en las últimas 24 horas.',
                'data'     => null,
                'duplicado_id' => (string) $duplicado->_id,
            ];
        }

        $fotoPath = null;
        if ($foto) {
            $fotoPath = $foto->store('evidencias', 'public');
        }

        $incidente = Incidente::create([
            'id_usuario'           => $data['id_usuario'],
            'id_tipo_incidente'    => (int) $data['id_tipo_incidente'],
            'latitud'              => $data['latitud'],
            'longitud'             => $data['longitud'],
            'descripcion'          => $data['descripcion'],
            'fecha_hora_incidente' => Carbon::now(),
            'fecha_hora_reporte'   => Carbon::now(),
            'evidencia_foto_url'   => $fotoPath,
            'direccion_aproximada' => $data['direccion_aproximada'],
        ]);

        // Notificar a autoridades vía notification-service
        $this->notificationService->notificarNuevoReporte($this->formatIncidente($incidente));

        return [
            'success' => true,
            'message' => 'Reporte enviado correctamente.',
            'data'    => $this->formatIncidente($incidente),
        ];
    }

    /**
     * Agregar comentario a un incidente.
     */
    public function agregarComentario(string $idIncidente, string $idUsuario, string $texto, bool $esAutoridad = false): array
    {
        $incidente = Incidente::find($idIncidente);

        if (!$incidente) {
            return [
                'success' => false,
                'message' => 'Incidente no encontrado.',
                'data'    => null,
            ];
        }

        $comentario = Comentario::create([
            'id_incidente' => $idIncidente,
            'id_usuario'   => $idUsuario,
            'comentario'   => $texto,
            'es_autoridad' => $esAutoridad,
        ]);

        return [
            'success' => true,
            'message' => 'Comentario agregado.',
            'data'    => [
                'id'           => (string) $comentario->_id,
                'id_incidente' => $comentario->id_incidente,
                'id_usuario'   => $comentario->id_usuario,
                'comentario'   => $comentario->comentario,
                'es_autoridad' => $comentario->es_autoridad,
                'created_at'   => $comentario->created_at,
            ],
        ];
    }

    /**
     * Formatear incidente para respuesta API.
     */
    private function formatIncidente(Incidente $incidente, bool $conComentarios = false): array
    {
        $data = [
            'id'                   => (string) $incidente->_id,
            'id_usuario'           => $incidente->id_usuario,
            'id_tipo_incidente'    => $incidente->id_tipo_incidente,
            'tipo_label'           => Incidente::tipoLabel((int) $incidente->id_tipo_incidente),
            'latitud'              => $incidente->latitud,
            'longitud'             => $incidente->longitud,
            'descripcion'          => $incidente->descripcion,
            'direccion_aproximada' => $incidente->direccion_aproximada,
            'evidencia_foto_url'   => $incidente->evidencia_foto_url,
            'fecha_hora_incidente' => $incidente->fecha_hora_incidente,
            'created_at'           => $incidente->created_at,
        ];

        if ($conComentarios && $incidente->relationLoaded('comentarios')) {
            $data['comentarios'] = $incidente->comentarios->map(fn($c) => [
                'id'           => (string) $c->_id,
                'id_usuario'   => $c->id_usuario,
                'comentario'   => $c->comentario,
                'es_autoridad' => $c->es_autoridad,
                'created_at'   => $c->created_at,
            ])->toArray();
        }

        return $data;
    }
}
