<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento: reporte validado o rechazado por autoridad.
 * Preparado para RabbitMQ/colas en el futuro.
 */
class ReporteValidadoEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly string $idUsuario,
        public readonly string $estado  // 'validado' | 'rechazado'
    ) {}
}
