<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CriticReview extends Model
{
    protected $table      = 'critic_reviews';
    protected $primaryKey = 'critic_review_id';
    public    $timestamps = false;

    protected $fillable = ['game_id','percent','avg_score','critic_rating','score','author','pub','text'];

    public function game() { return $this->belongsTo(Game::class, 'game_id', 'game_id'); }
}
