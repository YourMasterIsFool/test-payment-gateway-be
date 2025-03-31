<?php

namespace App\Repository;

use App\Dto\UserResponseDto;
use App\Models\User;

class UserRepository {
    public function findById(string $id): ?User {
        return User::where('id', $id)->first();
    }



    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}