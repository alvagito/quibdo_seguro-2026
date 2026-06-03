<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Canje extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'canjes';
    
    protected $fillable = [
        'id_usuario',
        'id_oferta',
        'id_comercio',
        'puntos_canjeados',
        'codigo_qr',
        'estado',
        'fecha_canje',
        'fecha_validacion'
    ];

    protected $casts = [
        'fecha_canje' => 'datetime',
        'fecha_validacion' => 'datetime'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function oferta()
    {
        return $this->belongsTo(Oferta::class, 'id_oferta');
    }

    public function comercio()
    {
        return $this->belongsTo(User::class, 'id_comercio');
    }
}
