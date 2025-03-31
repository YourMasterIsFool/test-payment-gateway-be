<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionQr extends Model
{
    //
    protected $fillable = ['reference_id' , 'qr_string', 'qr_id', 'amount', 'currency', 'channel_code', 'expire_at', 'business_id' , 'status'];
}
