<?php

namespace App\Services;

use App\Models\Notificacion;
use App\Models\User;

class NotificacionService
{
    /**
     * Crear notificación para un usuario
     */
    public function crear($idUsuario, $tipo, $titulo, $mensaje, $url = null, $datosAdicionales = [])
    {
        return Notificacion::create([
            'id_usuario' => $idUsuario,
            'tipo' => $tipo,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'leida' => false,
            'url' => $url,
            'datos_adicionales' => $datosAdicionales
        ]);
    }

    /**
     * Notificar validación de reporte
     */
    public function notificarValidacionReporte($incidente, $estado)
    {
        $estadoTexto = $estado == 2 ? 'validado' : 'rechazado';
        $titulo = $estado == 2 ? '✅ Reporte Validado' : '❌ Reporte Rechazado';
        
        $mensaje = "Tu reporte ha sido {$estadoTexto} por las autoridades.";
        
        if ($estado == 2) {
            $mensaje .= " Has ganado 10 puntos.";
        }

        return $this->crear(
            $incidente->id_usuario,
            'validacion_reporte',
            $titulo,
            $mensaje,
            '/perfil',
            ['id_incidente' => $incidente->_id]
        );
    }

    /**
     * Notificar nuevo reporte a autoridades
     */
    public function notificarNuevoReporte($incidente)
    {
        $autoridades = User::where('rol', 'autoridad')->get();
        
        foreach ($autoridades as $autoridad) {
            $this->crear(
                $autoridad->_id,
                'nuevo_reporte',
                '🚨 Nuevo Reporte',
                "Se ha reportado un incidente en {$incidente->direccion_aproximada}",
                "/autoridad/validar_accion/{$incidente->_id}",
                ['id_incidente' => $incidente->_id]
            );
        }
    }

    /**
     * Notificar canje exitoso
     */
    public function notificarCanje($usuario, $oferta, $codigoQR)
    {
        return $this->crear(
            $usuario->_id,
            'canje',
            '🎁 Canje Exitoso',
            "Has canjeado: {$oferta->titulo}. Código: {$codigoQR}",
            '/perfil',
            ['codigo_qr' => $codigoQR]
        );
    }

    /**
     * Marcar notificación como leída
     */
    public function marcarLeida($idNotificacion)
    {
        $notificacion = Notificacion::find($idNotificacion);
        if ($notificacion) {
            $notificacion->leida = true;
            $notificacion->save();
        }
    }

    /**
     * Obtener notificaciones no leídas de un usuario
     */
    public function noLeidas($idUsuario)
    {
        return Notificacion::where('id_usuario', $idUsuario)
            ->where('leida', false)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
