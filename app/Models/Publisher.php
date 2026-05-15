<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    protected $table = 'publishers'; 
    protected $primaryKey = 'id';
    public $timestamps = false;
}
