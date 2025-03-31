<?php
namespace App\Dto;


class CreateQrDto
{

    public function __construct(
        public readonly string $reference_id,
        public readonly string $type,
        public readonly string $currency,
        public readonly float $amount,
        public readonly string $expire_at,
        public readonly string $external_id,
        public readonly string $callback_url,


    ) {}
}