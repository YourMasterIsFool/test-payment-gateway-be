<?php

namespace App\Dto;
class CreateXenditTransactionDto
{   

    public function __construct(
        public string $external_id,
        public float $amount,
        public string $payer_email
    ) {

    }
}