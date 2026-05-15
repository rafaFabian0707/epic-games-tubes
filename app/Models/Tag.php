<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table      = 'tags';
    protected $primaryKey = 'tag_id';   // ← INI YANG KURANG
    public    $timestamps = false;       // ← tabel tags tidak punya created_at/updated_at

    protected $fillable = ['emoji', 'label'];

    // Relasi balik ke Game (opsional tapi bagus untuk konsistensi)
    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_tags', 'tag_id', 'game_id');
    }
}