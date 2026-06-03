<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'usuarios';
    
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
        'puntos'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relación: un usuario tiene muchos incidentes
    public function incidentes()
    {
        return $this->hasMany(Incidente::class, 'id_usuario');
    }

    // Relación: un comercio tiene muchas ofertas
    public function ofertas()
    {
        return $this->hasMany(Oferta::class, 'id_comercio');
    }

    // Alias para ofertas publicadas (usado en controladores)
    public function ofertasPublicadas()
    {
        return $this->ofertas();
    }

    // Relación: un usuario tiene muchas notificaciones
    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class, 'id_usuario');
    }

    // Relación: un usuario tiene muchos canjes
    public function canjes()
    {
        return $this->hasMany(Canje::class, 'id_usuario');
    }

    // Relación: un usuario tiene muchos comentarios
    public function comentarios()
    {
        return $this->hasMany(Comentario::class, 'id_usuario');
    }
}
