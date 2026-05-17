<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GameSocialLink extends Model
{
    protected $table      = 'game_social_links';
    protected $primaryKey = 'link_id';
    public    $timestamps = false;

    protected $fillable = ['game_id','platform','url'];

    public function game() { return $this->belongsTo(Game::class, 'game_id', 'game_id'); }
}
