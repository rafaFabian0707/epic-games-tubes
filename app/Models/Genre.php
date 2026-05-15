<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $primaryKey = 'genre_id';
    public $timestamps    = false;

    protected $fillable = ['name'];

    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_genre', 'genre_id', 'game_id');
    }
}
