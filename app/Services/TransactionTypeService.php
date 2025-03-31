<?php
namespace App\Services;
use App\Models\TransactionType;
use App\Repository\TransactionTypeRepository;
use App\Services\ResponseServices;
use Illuminate\Support\Facades\Http;

class TransactionTypeService   {

    protected $transactionRepo;
    protected $responseService;

    
    public function __construct(TransactionTypeRepository $transactionRepo, ResponseServices $response_services) {
        $this->transactionRepo = $transactionRepo;  
        $this->responseService = $response_services;
    }
    public function findByName($name):? TransactionType {
        $find = $this->transactionRepo->findByName($name);
        if($find) {
            return $find;
        }
        return $this->responseService->notFound('Transaction Type Not Found');
    }

    public function findByCode($code): ?TransactionType
    {
        $find = $this->transactionRepo->findByCode($code);
        if ($find) {
            return $find;
        }
        return $this->responseService->notFound('Transaction Type Not Found');
    }
}