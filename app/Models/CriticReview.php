<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CriticReview extends Model
{
    protected $table = 'critic_reviews'; 
    protected $primaryKey = 'id';
    public $timestamps = false;
}
