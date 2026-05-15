<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameSocialLink extends Model
{
    protected $table = 'game_social_links'; 
    protected $primaryKey = 'id';
    public $timestamps = false;
}
