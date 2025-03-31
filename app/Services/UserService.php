<?php
namespace App\Services;
use App\Dto\UserResponseDto;
use App\Models\User;
use App\Repository\UserRepository;

class UserService {
    protected $userRepo;
    protected $responseService;
    public function __construct(UserRepository  $userRepository, ResponseServices $responseService) {
        $this->userRepo = $userRepository;
        $this->responseService =  $responseService;
    }
    public function findById($id) {
        $findUser = $this->userRepo->findById($id);
        if(!$findUser) {
            return $this->responseService->notFound('User Not Found');
        }
        $userDto  = new UserResponseDto($findUser->name, $findUser->email);
        return $userDto;
    }

    public function findByEmail(string $email) {
        $findUser =  $this->userRepo->findByEmail($email);

        if(!$findUser) {
            return $this->responseService->notFound('User Not Found');
        }

        return $findUser;
    }
}