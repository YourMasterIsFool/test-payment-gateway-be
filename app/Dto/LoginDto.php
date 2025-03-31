<?php
namespace App\Dto;

class LoginDto {
    public function __construct(
        public string $email,
        public string $password,
    )
    {
        
    }
}