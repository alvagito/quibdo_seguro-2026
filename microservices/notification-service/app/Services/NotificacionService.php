<?php

namespace App\Services;

use App\Models\Notificacion;

class NotificacionService
{
    /**
     * Crear una notificación para un usuario.
     */
    public function crear(
        string $idUsuario,
        string $tipo,
        string $titulo,
        string $mensaje,
        ?string $url = null,
        array $datosAdicionales = []
    ): Notificacion {
        return Notificacion::create([
            'id_usuario'        => $idUsuario,
            'tipo'              => $tipo,
            'titulo'            => $titulo,
            'mensaje'           => $mensaje,
            'leida'             => false,
            'url'               => $url,
            'datos_adicionales' => $datosAdicionales,
        ]);
    }

    /**
     * Obtener notificaciones de un usuario.
     */
    public function obtenerDeUsuario(string $idUsuario): array
    {
        return Notificacion::where('id_usuario', $idUsuario)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($n) => $this->format($n))
            ->toArray();
    }

    /**
     * Marcar notificación como leída.
     */
    public function marcarLeida(string $id, string $idUsuario): array
    {
        $notificacion = Notificacion::where('_id', $id)
            ->where('id_usuario', $idUsuario)
            ->first();

        if (!$notificacion) {
            return [
                'success' => false,
                'message' => 'Notificación no encontrada.',
                'data'    => null,
            ];
        }

        $notificacion->leida = true;
        $notificacion->save();

        return [
            'success' => true,
            'message' => 'Notificación marcada como leída.',
            'data'    => $this->format($notificacion),
        ];
    }

    /**
     * Contar notificaciones no leídas de un usuario.
     */
    public function contarNoLeidas(string $idUsuario): int
    {
        return Notificacion::where('id_usuario', $idUsuario)
            ->where('leida', false)
            ->count();
    }

    private function format(Notificacion $n): array
    {
        return [
            'id'                => (string) $n->_id,
            'tipo'              => $n->tipo,
            'titulo'            => $n->titulo,
            'mensaje'           => $n->mensaje,
            'leida'             => $n->leida,
            'url'               => $n->url,
            'datos_adicionales' => $n->datos_adicionales,
            'created_at'        => $n->created_at,
        ];
    }
}
