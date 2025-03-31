<?php
namespace App\Http\Controllers;

use App\Data\DepositData;
use App\Dto\CreateDepositDto;
use App\Dto\CreateQrDto;
use App\Services\ResponseServices;
use App\Services\XenditServices;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    //

    protected $xenditService;
    protected $responseService;
    public function __construct(XenditServices $xenditService, ResponseServices $responseService) {
        $this->xenditService = $xenditService;
        $this->responseService = $responseService;
    }
    // deposit
    public function deposit(Request $request) {
        try { 
            $validated = DepositData::from($request);
        }
        catch (\Exception $e) {
            return $this->responseService->unProcessableEntity($e->errors());
        }
        $userId =  $request->user()->id;
        $dto = new CreateQrDto($validated->order_id, 'DYNAMIC', 'IDR', $validated->amount, now()->addDay(1)->toISOString(), $validated->order_id, env('CALLBACK_URL'));
        $deposit =  $this->xenditService->createQrCodeTransactionDeposit($dto, $userId);
        return $this->responseService->successResponse($deposit);

    }
    public function simulateDeposit($order_id) {
        $simulate = $this->xenditService->simulateQrCode($order_id);
        return $this->responseService->successResponse(null, 'Sucessfully simulate order '.$order_id, );
    }

    
}
