<?php
namespace App\Services;

use App\Dto\CreateDepositDto;
use App\Dto\CreateQrCodeDto;
use App\Dto\CreateQrDto;
use App\Dto\CreateTransactionDto as DtoCreateTransactionDto;
use App\Dto\CreateWithdrawDto;
use App\Dto\CreateXenditTransactionDto;
use App\Repository\QrRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\HttpException;

class XenditServices {

    protected $masterStatusService;
    protected $userService;
    protected $paketService;
    protected $transactionTypeService;


    public function __construct(MasterStatusService $master_status_service, UserService $userService, 
    TransactionTypeService $transaction_type,
    public TransactionService $transactionService,
    public ResponseServices $responseService,
    public QrRepository $qrRepository,
    public QrService $qrService,

    ) {
        $this->masterStatusService =  $master_status_service;
        $this->userService =  $userService;
        $this->transactionTypeService =  $transaction_type;
    }
    public function deposit(CreateDepositDto $deposit) {
        DB::beginTransaction();
        try {
            $findUser = $this->userService->findById($deposit->userId);
            $findDeposit = $this->transactionTypeService->findByCode('deposit');
            $findWaiting = $this->masterStatusService->findByCode('pending');
            $uuid =  Uuid::uuid4();
            $depositPayload =  new CreateXenditTransactionDto($deposit->orderId, $deposit->amount, $findUser->email);
            $xenditApiKey = env('XENDIT_WRITE_API_KEY');
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($xenditApiKey . ':'),
                'Content-Type' => 'application/json',
            ])
                ->post(env('XENDIT_API_URL') . '/v2/invoices', $depositPayload);
            $response = $response->json();

            $transactionDto =  new DtoCreateTransactionDto(
                $response['external_id'],
                $findDeposit->id,
                $findWaiting->id,
                $deposit->userId,
                $response['amount'],
                $deposit->timestamp
            );

            $createdTransaction = $this->transactionService->create($transactionDto);
            DB::commit();
            return $createdTransaction;

        }
        catch(\Exception $e) {

            DB::rollBack();
            return $this->responseService->internalServer(null, 'Error When Deposit into xendit');
        }

    
    }

   

    public function simulateQrCode($orderId) {
        $findQr  =  $this->qrService->findByExternalId($orderId);
           $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode(env('XENDIT_WRITE_API_KEY') . ':'),
                'Content-Type' => 'application/json',
                'api-version' => '2022-07-31',

            ])->post(env('XENDIT_API_URL') . '/qr_codes/' . $findQr->qr_id . '/payments/simulate', [
                'amount' => (int) $findQr->amount
            ]);

            if ($response->failed()) {
                return $this->responseService->internalServer($response['message'], 'Failed created xendit qr code');
            }
            return $this->responseService->successResponse(null, 'Successfully simulate qr code');
    }

    public function withdraw(CreateWithdrawDto $withdrawDto) {
        DB::beginTransaction();
        try {
            $findUser = $this->userService->findById($withdrawDto->userId);
            $findWithdraw = $this->transactionTypeService->findByCode('withdraw');
            $findWaiting = $this->masterStatusService->findByCode('pending');
            $uuid =  Uuid::uuid4();
            $withdrawPayload =  $withdrawDto;
            $xenditApiKey = env('XENDIT_WRITE_API_KEY');
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($xenditApiKey . ':'),
                'Content-Type' => 'application/json',
            ])
                ->post(env('XENDIT_API_URL') . '/disbursements', $withdrawPayload);

            if($response->failed()) {
                
                return $this->responseService->internalServer(null, 'Error for distburment xendit');
            }
            $response = $response->json();
            $transactionDto =  new DtoCreateTransactionDto(
                $response['external_id'],
                $findWithdraw->id,
                $findWaiting->id,
                $withdrawDto->userId,
                $response['amount'],
                $withdrawDto->timestamp
            );
            DB::commit();
            $transcation =  $this->transactionService->create($transactionDto);

            return $transcation;
            // return $this->responseService->successResponse($, 'Successfully created transaction');
        } catch (\Exception $e) {
            Log::emergency($e);

            DB::rollBack(); 
            return $this->responseService->internalServer(null, 'Error save in to withdraw');
        }
    }

    public function createQrCodeTransactionDeposit(CreateQrDto $transaction_qr, $userId) {
        DB::beginTransaction();
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode(env('XENDIT_WRITE_API_KEY') . ':'),
                'Content-Type' => 'application/json',
              
            ])->post(env('XENDIT_API_URL') . '/qr_codes', $transaction_qr);

  
            if ($response->failed()) {
                return $this->responseService->internalServer($response->json(), 'Error when created qr code xendit');
            }
            $createdQr =  $this->qrRepository->create($response, $transaction_qr->expire_at);
            $findUser = $this->userService->findById($userId);
            $findDeposit = $this->transactionTypeService->findByCode('deposit');
            $findWaiting = $this->masterStatusService->findByCode('pending');
            $transactionDto =  new DtoCreateTransactionDto(
                $response['external_id'],
                $findDeposit->id,
                $findWaiting->id,
                $userId,
                $response['amount'],
                $transaction_qr->expire_at,
            );

            $createdTransaction =  $this->transactionService->create($transactionDto);
            // simulate the transaction using qr code
            // $this->simulateQrCode($createdTransaction->external_id);
            DB::commit();
            return $createdTransaction;
        } catch (\Exception $e) {
            Log::emergency($e);
            DB::rollBack();
            return $this->responseService->internalServer(null, $e->getMessage());
        }
    }


    public function callback($request) {
        $reference_id =  null;
        $status =  $request['status'];
        if($request['event'] == 'qr.payment'){
                $reference_id =  $request['qr_code']['external_id'];
        }
        else {
            $reference_id = $request['external_id'];
        }
       $findTransaction =  $this->transactionService->findByExternalId($reference_id);
       $statusSuccess =  $this->masterStatusService->findByCode('success');
       if($findTransaction->transaction_status_id != $statusSuccess->id) {
       

            if($status == 'COMPLETED') {
                        $this->transactionService->updateSuccessStatus($findTransaction->external_id);
            }
            else {
                    $this->transactionService->updateStatusFailed($findTransaction->external_id);
            }

       }
       $findTransaction =  $this->transactionService->findByExternalId($reference_id);

       return $findTransaction;

    }



   
}