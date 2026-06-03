<?php

namespace App\Interfaces;

/**
 * Contrato para operaciones de recompensas y canjes.
 * Preparado para implementaciones alternativas (eventos, colas).
 */
interface RewardInterface
{
    /**
     * Canjear una oferta por puntos.
     */
    public function canjear(string $idUsuario, string $idOferta): array;

    /**
     * Validar un canje con código QR.
     */
    public function validarCanje(string $codigoQr, string $idComercio): array;

    /**
     * Obtener canjes de un usuario.
     */
    public function canjesDeUsuario(string $idUsuario): array;
}
