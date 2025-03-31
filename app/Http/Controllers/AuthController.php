<?php

namespace App\Http\Controllers;

use App\Data\LoginData;
use App\Dto\LoginDto;
use App\Services\AuthService;
use App\Services\ResponseServices;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //

    public function __construct(
        public AuthService $authService,
        public ResponseServices $responseService,

    )
    {
        
    }

    public function login(Request $request) {   
        try {
            $validation = LoginData::from($request);
        }
        catch(\Exception $e) {
            return $this->responseService->unProcessableEntity($e->errors(), 'Please check data input');
        }
        $token =  $this->authService->login($validation->dataDto());
        return $this->responseService->successResponse($token);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->responseService->successResponse(null, 'Successfully logout');
    }
}
