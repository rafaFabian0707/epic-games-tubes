<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{
    protected $table = 'developers'; 
    protected $primaryKey = 'id';
    public $timestamps = false;
}
