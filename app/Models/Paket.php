<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paket extends Model
{
    //

    protected $fillable = [
        'code',
        'price',
        'is_active',
    ];
  
}
