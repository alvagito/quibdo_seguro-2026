<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Recompensa extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'recompensas';

    protected $fillable = [
        'nombre',
        'puntos',
    ];

    protected $casts = [
        'puntos' => 'integer',
    ];
}
