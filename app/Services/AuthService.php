<?php
namespace  App\Services;

use App\Dto\LoginDto;
use Illuminate\Support\Facades\Hash;

class AuthService {
    

    public function __construct(
        public UserService $userService,
        public ResponseServices $responseService,
    )
    {
        
    }
    public function login(LoginDto $schema) {
        $findUser = $this->userService->findByEmail($schema->email);
        $checkPassword =  Hash::check($schema->password, $findUser->password);

        if(!$checkPassword) {
            return $this->responseService->badRequestResponse([
                'password' => "Password tidak sama"
            ], 'Incorected Password');
        }

          $token = $findUser->createToken('auth_token')->plainTextToken;
          return $token;


    }
}