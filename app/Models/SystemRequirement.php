<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SystemRequirement extends Model
{
    protected $table      = 'system_requirements';
    protected $primaryKey = 'req_id';
    public    $timestamps = false;

    protected $fillable = [
        'game_id',
        'min_os','min_cpu','min_ram_gb','min_gpu','min_storage_gb',
        'rec_os','rec_cpu','rec_ram_gb','rec_gpu','rec_storage_gb',
        'languange','policy',
    ];

    public function game() { return $this->belongsTo(Game::class, 'game_id', 'game_id'); }
}
