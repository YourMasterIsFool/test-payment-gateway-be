<?php
namespace App\Dto;
class CreateQrCodeDto
{
    public function __construct(
        public string $reference_id,
        public string $type='DYNAMIC',
        public string $currency = 'IDR',
        public float $amount,
        public string $expire_at
    ) {}
}