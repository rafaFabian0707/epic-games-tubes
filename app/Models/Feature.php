<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $primaryKey = 'feature_id';
    public $timestamps    = false;

    protected $fillable = ['name'];

    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_features', 'feature_id', 'game_id');
    }
}
