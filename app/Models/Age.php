<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Age extends Model
{
    protected $table      = 'age'; 
    protected $primaryKey = 'age_id'; 
    
    // FIX 1: Migration proyek v4.0 memiliki timestamps(), wajib set TRUE
    public $timestamps = true;

    // FIX 2: Daftarkan kolom agar aman dari Mass Assignment
    protected $fillable = [
        'game_id',
        'age',
        'desc',
    ];

    /**
     * Relasi Inverse: Satu data age rating dimiliki oleh satu game.
     */
    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id', 'game_id');
    }
}