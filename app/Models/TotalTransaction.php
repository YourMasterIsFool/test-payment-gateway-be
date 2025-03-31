<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TotalTransaction extends Model
{
    //
    protected $fillable = [
        'total_saldo',
        'total_penarikan',
        'user_id',
    ];
}
