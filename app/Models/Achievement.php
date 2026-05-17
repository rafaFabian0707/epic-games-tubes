<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $table      = 'achievements';
    protected $primaryKey = 'achievement_id';
    public    $timestamps = false;

    protected $fillable = ['game_id','total','avail_xp','name','desc','acv_xp','percent','icon_url'];

    public function game() { return $this->belongsTo(Game::class, 'game_id', 'game_id'); }
}
