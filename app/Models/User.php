<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $fillable = ['username','email','password','full_name','is_admin','is_active'];
    protected $hidden = ['password','remember_token'];
    protected function casts(): array {
        return ['password'=>'hashed','is_admin'=>'boolean','is_active'=>'boolean'];
    }
    public function transactions() { return $this->hasMany(Transaction::class,'user_id','user_id'); }
    public function library() { return $this->hasMany(Library::class,'user_id','user_id'); }
    public function wishlists() { return $this->hasMany(Wishlist::class,'user_id','user_id'); }
    public function ownedGames() {
        return $this->belongsToMany(Game::class,'library','user_id','game_id','user_id','game_id')->withPivot('acquired_at');
    }
    public function alreadyOwns(int|array $gameIds): bool {
        $ids = is_array($gameIds) ? $gameIds : [$gameIds];
        // SQL equivalent:
        // SELECT EXISTS(
        //   SELECT 1 FROM library
        //   WHERE user_id = ? AND game_id IN (?, ?, ...)
        // )
        return $this->library()->whereIn('game_id',$ids)->exists();
    }
    public function ownedFromList(array $gameIds) {
        // SQL equivalent:
        // SELECT game_id FROM library
        // WHERE user_id = ? AND game_id IN (?, ?, ...)
        return $this->library()->whereIn('game_id',$gameIds)->pluck('game_id');
    }
}
