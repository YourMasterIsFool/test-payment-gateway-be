<?php
namespace App\Dto;
class CreateTransactionDto {
    public function __construct(
        public string $external_id,
        public string $transaction_type_id,
        public string $transaction_status_id,
        public string $user_id,
        public string $amount,
        public string $timestamp,


    )
    {
    }
}