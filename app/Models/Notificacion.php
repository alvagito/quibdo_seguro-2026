<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Notificacion extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'notificaciones';
    
    protected $fillable = [
        'id_usuario',
        'tipo',
        'titulo',
        'mensaje',
        'leida',
        'url',
        'datos_adicionales'
    ];

    protected $casts = [
        'leida' => 'boolean',
        'datos_adicionales' => 'array'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
