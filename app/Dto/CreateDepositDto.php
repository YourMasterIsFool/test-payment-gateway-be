<?php
namespace App\Dto;
use Illuminate\Support\Facades\Date;

class CreateDepositDto
{   
    public function __construct(
        public string $userId,
        public float $amount,
        public string $timestamp,
        public string $orderId,
    ) {
       
}
}