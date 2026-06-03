<?php

namespace App\Services;

use App\Interfaces\RewardInterface;
use App\Models\Canje;
use App\Models\Oferta;
use App\Models\Recompensa;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RewardService implements RewardInterface
{
    /**
     * Generar código QR único con formato QBS-{timestamp}-{random}
     */
    private function generarCodigoQR(): string
    {
        return 'QBS-' . time() . '-' . strtoupper(substr(md5(uniqid('', true)), 0, 6));
    }

    /**
     * Obtener puntos actuales del usuario desde auth-service.
     */
    private function getPuntosUsuario(string $idUsuario): int
    {
        try {
            $authUrl = rtrim(config('services.auth_service.url'), '/');
            $response = Http::timeout(5)->get("{$authUrl}/api/auth/user/{$idUsuario}");

            if ($response->successful()) {
                return (int) $response->json('data.puntos', 0);
            }
        } catch (\Exception $e) {
            Log::error('RewardService::getPuntosUsuario error: ' . $e->getMessage());
        }

        return 0;
    }

    /**
     * Actualizar puntos del usuario en auth-service.
     */
    private function actualizarPuntos(string $idUsuario, int $puntos): bool
    {
        try {
            $authUrl = rtrim(config('services.auth_service.url'), '/');
            $response = Http::timeout(5)->patch("{$authUrl}/api/auth/user/{$idUsuario}/puntos", [
                'puntos' => $puntos,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('RewardService::actualizarPuntos error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Notificar canje al notification-service.
     */
    private function notificarCanje(string $idUsuario, string $tituloOferta, string $codigoQR): void
    {
        try {
            $notifUrl = rtrim(config('services.notification_service.url'), '/');
            Http::timeout(5)->post("{$notifUrl}/api/notificaciones/canje", [
                'id_usuario'  => $idUsuario,
                'titulo_oferta' => $tituloOferta,
                'codigo_qr'   => $codigoQR,
            ]);
        } catch (\Exception $e) {
            Log::error('RewardService::notificarCanje error: ' . $e->getMessage());
        }
    }

    /**
     * POST /api/canjear
     */
    public function canjear(string $idUsuario, string $idOferta): array
    {
        $oferta = Oferta::find($idOferta);

        if (!$oferta) {
            return [
                'success' => false,
                'message' => 'Oferta no encontrada.',
                'data'    => null,
            ];
        }

        $puntosActuales = $this->getPuntosUsuario($idUsuario);

        if ($puntosActuales < $oferta->puntos) {
            return [
                'success' => false,
                'message' => "No tienes suficientes puntos. Necesitas {$oferta->puntos}, tienes {$puntosActuales}.",
                'data'    => null,
            ];
        }

        $codigoQR = $this->generarCodigoQR();

        $canje = Canje::create([
            'id_usuario'      => $idUsuario,
            'id_oferta'       => (string) $oferta->_id,
            'id_comercio'     => $oferta->id_comercio,
            'puntos_canjeados' => $oferta->puntos,
            'codigo_qr'       => $codigoQR,
            'estado'          => 'pendiente',
            'fecha_canje'     => now(),
        ]);

        // Restar puntos en auth-service
        $this->actualizarPuntos($idUsuario, $puntosActuales - $oferta->puntos);

        // Notificar al usuario
        $this->notificarCanje($idUsuario, $oferta->titulo, $codigoQR);

        return [
            'success' => true,
            'message' => "Has canjeado: {$oferta->titulo}.",
            'data'    => [
                'id'          => (string) $canje->_id,
                'codigo_qr'   => $codigoQR,
                'oferta'      => $oferta->titulo,
                'puntos'      => $oferta->puntos,
                'estado'      => 'pendiente',
                'fecha_canje' => $canje->fecha_canje,
            ],
        ];
    }

    /**
     * POST /api/validar-canje
     */
    public function validarCanje(string $codigoQr, string $idComercio): array
    {
        $canje = Canje::where('codigo_qr', $codigoQr)
            ->where('id_comercio', $idComercio)
            ->where('estado', 'pendiente')
            ->first();

        if (!$canje) {
            return [
                'success' => false,
                'message' => 'Código inválido, ya utilizado, o no pertenece a este comercio.',
                'data'    => null,
            ];
        }

        $canje->estado           = 'validado';
        $canje->fecha_validacion = now();
        $canje->save();

        return [
            'success' => true,
            'message' => 'Canje validado correctamente.',
            'data'    => [
                'id'               => (string) $canje->_id,
                'codigo_qr'        => $canje->codigo_qr,
                'id_usuario'       => $canje->id_usuario,
                'puntos_canjeados' => $canje->puntos_canjeados,
                'estado'           => 'validado',
                'fecha_validacion' => $canje->fecha_validacion,
            ],
        ];
    }

    /**
     * GET /api/canjes
     */
    public function canjesDeUsuario(string $idUsuario): array
    {
        $canjes = Canje::where('id_usuario', $idUsuario)
            ->with('oferta')
            ->orderBy('created_at', 'desc')
            ->get();

        return $canjes->map(fn($c) => [
            'id'               => (string) $c->_id,
            'codigo_qr'        => $c->codigo_qr,
            'puntos_canjeados' => $c->puntos_canjeados,
            'estado'           => $c->estado,
            'fecha_canje'      => $c->fecha_canje,
            'fecha_validacion' => $c->fecha_validacion,
            'oferta'           => $c->oferta ? [
                'id'          => (string) $c->oferta->_id,
                'titulo'      => $c->oferta->titulo,
                'descripcion' => $c->oferta->descripcion,
            ] : null,
        ])->toArray();
    }

    /**
     * Listar todas las recompensas y ofertas disponibles.
     */
    public function listarRecompensas(): array
    {
        return [
            'recompensas' => Recompensa::all()->map(fn($r) => [
                'id'     => (string) $r->_id,
                'nombre' => $r->nombre,
                'puntos' => $r->puntos,
            ])->toArray(),
            'ofertas' => Oferta::all()->map(fn($o) => [
                'id'          => (string) $o->_id,
                'id_comercio' => $o->id_comercio,
                'titulo'      => $o->titulo,
                'descripcion' => $o->descripcion,
                'puntos'      => $o->puntos,
            ])->toArray(),
        ];
    }

    /**
     * Estadísticas de un comercio.
     */
    public function estadisticasComercio(string $idComercio): array
    {
        return [
            'total_ofertas'    => Oferta::where('id_comercio', $idComercio)->count(),
            'canjes_validados' => Canje::where('id_comercio', $idComercio)->where('estado', 'validado')->count(),
            'puntos_redimidos' => (int) Canje::where('id_comercio', $idComercio)->where('estado', 'validado')->sum('puntos_canjeados'),
            'canjes_pendientes' => Canje::where('id_comercio', $idComercio)->where('estado', 'pendiente')->count(),
        ];
    }
}
