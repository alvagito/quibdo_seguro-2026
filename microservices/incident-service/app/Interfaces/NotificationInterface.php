<?php

namespace App\Interfaces;

/**
 * Contrato para comunicación con el Notification Service.
 * Preparado para reemplazar con RabbitMQ/eventos en el futuro.
 */
interface NotificationInterface
{
    /**
     * Notificar a las autoridades sobre un nuevo reporte.
     */
    public function notificarNuevoReporte(array $incidente): bool;

    /**
     * Notificar al usuario sobre la validación de su reporte.
     */
    public function notificarValidacionReporte(string $idUsuario, string $estado): bool;
}
