<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    //

    protected $fillable = [
            'external_id',
            'amount',
            'uuid',
            'user_id',
            'transaction_type_id',
            'transaction_status_id',
            'timestamp',
    ];
    public function status() {
        return $this->belongsTo(TransactionStatus::class, 'transaction_status_id', 'id');
    }

    public function type()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id', 'id');
    }
}
