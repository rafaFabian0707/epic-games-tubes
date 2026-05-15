<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    protected $table      = 'platform'; // nama tabel singular sesuai migration
    protected $primaryKey = 'platform_id';
    public $timestamps    = false;

    protected $fillable = ['platform'];

    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_platform', 'platform_id', 'game_id');
    }
}
