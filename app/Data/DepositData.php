<?php
namespace App\Data;

use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class DepositData extends Data {
public function __construct(
        #[Required]
        public string $amount,

        #[Required]
        public string $order_id,

        #[Required]
        public string $timestamp, // Change Date to string
) {}

public static function rules(): array {
    return [
    'amount' => 'required|numeric',
    'order_id' => 'required|string',
    'timestamp' => 'required|date',
    ];
}
}