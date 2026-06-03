<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento: nuevo reporte de incidente creado.
 * Preparado para RabbitMQ/colas en el futuro.
 */
class NuevoReporteEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly array $incidente) {}
}
