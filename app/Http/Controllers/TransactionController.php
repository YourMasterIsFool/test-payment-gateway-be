<?php

namespace App\Http\Controllers;

use App\Services\ResponseServices;
use App\Services\TransactionService;
use App\Services\XenditServices;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    //  


    


    public function __construct(
        public TransactionService $transactionService,
        public ResponseServices $responseServices,
        public XenditServices $xenditServices,
    ){

    }

    public function findAll(Request $request) {
        $findAll =  $this->transactionService->findAll();
        return $this->responseServices->successResponse($findAll, "Successfully get list transaction");
    }

    public function findOne($orderId) {
        $find =  $this->transactionService->findOne($orderId);
        return $this->responseServices->successResponse($find);
    }

    public function simulateDeposit($order_id)
    {
        $simulate = $this->xenditServices->simulateQrCode($order_id);
        return $this->responseServices->successResponse(null, 'Sucessfully simulate order ' . $order_id,);
    }
}
