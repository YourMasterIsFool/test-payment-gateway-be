<?php

namespace App\Http\Controllers;

use App\Data\WithdrawData;
use App\Dto\CreateWithdrawDto;
use App\Services\ResponseServices;
use App\Services\XenditServices;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    //

  
    public function __construct(
       public ResponseServices $responseService,
        public XenditServices $xenditService
    )
    {
        
    }

    public function withdraw(Request $request)
    {
        try {
            $validated = WithdrawData::from($request);
        } catch (\Exception $e) {
            return $this->responseService->unProcessableEntity($e->errors());
        }
        $userId =  $request->user()->id;
        $dto = new CreateWithdrawDto($validated->order_id, $validated->amount, $validated->timestamp, env('TEST_BANK_CODE'), env('TEST_ACCOUNT_NUMBER'), $userId, env('TEST_ACCOUNT_HOLDER_NAME') );
        $withdraw =  $this->xenditService->withdraw($dto);
        return $this->responseService->successResponse($withdraw);
    }
}
