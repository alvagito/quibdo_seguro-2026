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
        'puntos',
    ];

    protected $casts = [
        'puntos' => 'integer',
    ];

    public function canjes()
    {
        return $this->hasMany(Canje::class, 'id_oferta');
    }
}
