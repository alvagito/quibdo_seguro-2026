<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use MongoDB\Laravel\Eloquent\DocumentModel;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use DocumentModel;

    protected $connection = 'mongodb';
    protected $collection = 'personal_access_tokens';

    protected $fillable = [
        'name',
        'token',
        'abilities',
        'expires_at',
        'last_used_at',
    ];

    protected $casts = [
        'abilities'     => 'array',
        'last_used_at'  => 'datetime',
        'expires_at'    => 'datetime',
    ];

    protected $hidden = [
        'token',
    ];
}
