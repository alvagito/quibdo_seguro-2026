<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Comentario extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'comentarios';
    
    protected $fillable = [
        'id_incidente',
        'id_usuario',
        'comentario',
        'es_autoridad'
    ];

    protected $casts = [
        'es_autoridad' => 'boolean'
    ];

    public function incidente()
    {
        return $this->belongsTo(Incidente::class, 'id_incidente');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
