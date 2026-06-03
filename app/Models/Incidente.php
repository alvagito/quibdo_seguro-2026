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
        'id_estado',
        'latitud',
        'longitud',
        'descripcion',
        'fecha_hora_incidente',
        'fecha_hora_reporte',
        'evidencia_foto_url',
        'direccion_aproximada',
        'comentarios_autoridad',
        'validado_por',
        'fecha_validacion'
    ];

    protected $casts = [
        'id_tipo_incidente' => 'integer',
        'id_estado' => 'integer',
        'latitud' => 'float',
        'longitud' => 'float',
        'fecha_hora_incidente' => 'datetime',
        'fecha_hora_reporte' => 'datetime',
        'fecha_validacion' => 'datetime',
    ];

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }


}
