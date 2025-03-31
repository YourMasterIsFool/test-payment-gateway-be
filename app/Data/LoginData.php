<?php

namespace App\Data;

use App\Dto\LoginDto;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class LoginData extends Data
{
    public function __construct(
        #[Required]
        public string $email,

        #[Required]
        public string $password
    ) {}

    public static function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function dataDto() {
        return new LoginDto($this->email, $this->password);
    }
}
