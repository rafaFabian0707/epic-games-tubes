<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'username',
        'email',
        'password',
        'full_name',
        'is_active'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
