<?php
namespace App\Dto;


class CreateWithdrawDto {

    public function __construct(
        public string $external_id,
        public float $amount,
        public string $timestamp,
        public string $bank_code,
        public string $account_number,
        public string $userId,
        public string $account_holder_name,

    )
    {
        
    }
}