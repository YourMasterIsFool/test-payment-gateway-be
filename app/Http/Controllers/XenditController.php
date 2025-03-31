<?php

namespace App\Http\Controllers;

use App\Services\QrService;
use App\Services\ResponseServices;
use App\Services\XenditServices;
use Illuminate\Http\Request;

class XenditController extends Controller
{
    //
    
    public function __construct(
        public QrService $qrService,
        public ResponseServices $responseService,
        public XenditServices $xendit_services,
    )
    {
        
    }
    public function callback(Request $request)
    {
       $result =  $this->xendit_services->callback($request);
        
       return $this->responseService->successResponse($result);
    }

    public function qr_simulate($order_id)
    {
        $simulate  = $this->qrService->simulate($order_id);
        return $this->responseService->successResponse($simulate, 'Successfully simulate qr code');
    }
}
