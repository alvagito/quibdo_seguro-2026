<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Oferta extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'ofertas';
    
    protected $fillable = [
        'id_comercio',
        'titulo',
        'descripcion',
        'puntos'
    ];

    // Relación con el comercio (usuario)
    public function comercio()
    {
        return $this->belongsTo(User::class, 'id_comercio');
    }

    // Relación: una oferta tiene muchos canjes
    public function canjes()
    {
        return $this->hasMany(Canje::class, 'id_oferta');
    }
}
