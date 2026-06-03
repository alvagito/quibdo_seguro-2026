<?php
namespace App\Models;
use MongoDB\Laravel\Auth\User as Authenticatable;
class User extends Authenticatable {
    protected $connection = 'mongodb';
    protected $collection = 'usuarios';
    protected $fillable = ['nombre','email','password','rol','puntos'];
    protected $hidden = ['password'];
}
