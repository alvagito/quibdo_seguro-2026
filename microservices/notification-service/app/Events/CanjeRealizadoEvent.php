<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Evento: canje de recompensa realizado.
 * Preparado para RabbitMQ/colas en el futuro.
 */
class CanjeRealizadoEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly string $idUsuario,
        public readonly string $tituloOferta,
        public readonly string $codigoQR
    ) {}
}
