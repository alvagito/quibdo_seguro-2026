<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Incidente extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'incidentes';
    public $timestamps = true;

    protected $fillable = [
        'id_usuario',
        'id_tipo_incidente',
        'latitud',
        'longitud',
        'descripcion',
        'fecha_hora_incidente',
        'fecha_hora_reporte',
        'evidencia_foto_url',
        'direccion_aproximada',
    ];

    protected $casts = [
        'id_tipo_incidente'    => 'integer',
        'fecha_hora_incidente' => 'datetime',
        'fecha_hora_reporte'   => 'datetime',
    ];

    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'id_incidente');
    }

    /**
     * Mapa de tipos de incidente.
     */
    public static function tipoLabel(int $tipo): string
    {
        return match ($tipo) {
            1 => 'Robo',
            2 => 'Accidente',
            3 => 'Violencia',
            4 => 'Otro',
            default => 'Desconocido',
        };
    }
}
