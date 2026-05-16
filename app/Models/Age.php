<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Age extends Model
{
    protected $table = 'age'; 
    protected $primaryKey = 'age_id';
    public $timestamps = false;
}
