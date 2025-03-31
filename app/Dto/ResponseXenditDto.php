<?php

namespace App\Dto;
use App\Models\TransactionType;
use App\Models\TransactionStatus;   

class ResponseXenditDto {

    
    public function __construct(
        public float $amount,
        public string $external_id,
        public string $created_at)
    {
        
    }
}