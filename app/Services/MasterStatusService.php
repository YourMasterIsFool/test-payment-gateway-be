<?php
namespace App\Services;
use App\Models\TransactionStatus;
use App\Models\TransactionType;

class MasterStatusService {


    protected $responseService;

    public function __construct(ResponseServices $responseService) 
    {
        $this->responseService = $responseService;
    }
    public function findByName(string $name) {
        
        $findByName =  TransactionStatus::where('name', $name)->first();
        if($findByName) {
            return $this->responseService->successResponse($findByName);
        }
        return $this->responseService->notFound();
    }


    public function findByCode(string $code)
    {
        $code =  TransactionStatus::where('code', $code)->first();
        if ($code) {
            return $code;
        }
        return $this->responseService->notFound(null, 'Status Tidak Ada');
    }
}