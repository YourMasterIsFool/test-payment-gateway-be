<?php
namespace App\Services;

use App\Dto\CreateQrDto;
use App\Dto\CreateTransactionDto as DtoCreateTransactionDto;
use App\Events\PaymentSuccess;
use App\Repository\TransactionRepository;
use CreateTransactionDto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionService {

    public function __construct(
      public  TransactionRepository $transactionRepository,
      public ResponseServices $responseService,
      public MasterTotalService $masterTotalService,
     public MasterStatusService $masterStatusService,
    public QrService $qrService,

    )
    {
    }
    public function create(DtoCreateTransactionDto $transcation) {
        DB::beginTransaction();
        try {
            $created =  $this->transactionRepository->create($transcation);
            DB::commit();
            return $created;
        }
        catch(\Exception $e ) {
            DB::rollBack();
            Log::emergency($e);
            return $this->responseService->internalServer($e);
        }
    }
    
    public function findOne($orderId) {
        $transaction = $this->findByExternalId($orderId);
        $findQrCodeDetail = $this->qrService->findByExternalId($orderId);
        $qrCode = $this->qrService->generateQrCodeByExternalId($orderId);

        $data =  $transaction;
        // $data['qr_code'] = $qrCode;

        $data = [
            'order_id' =>  $transaction->external_id,
            'amount' => $transaction->amount,
            'status' => $transaction->status->name,
            'qr_code' => $qrCode,
            'expiret_at' => $findQrCodeDetail->expire_at
        ];

        return $data;
    }

    public function findAll($userId = null) {
        try {
            $findAll =  $this->transactionRepository->findAll($userId);
            return $findAll;
        }
        catch(\Exception $e ) {
            dd($e);
            return $this->responseService->internalServer($e);
        }
    }

    public function findByExternalId($externalId) {
        try {
            $findByExternalId =  $this->transactionRepository->findByExternalId($externalId);
            return $findByExternalId;
        }
        catch(\Exception $e ) {
            return $this->responseService->notFound(null, 'Transaction Not Found with external id');
        }
    }

    public function updateSuccessStatus($externalId) {
        DB::beginTransaction();
        try {
            $transaction =  $this->findByExternalId($externalId);
            $findSuccessStatus =  $this->masterStatusService->findByCode('success');
            $updateTransaction =  $this->transactionRepository->update($transaction->id, [
                'transaction_status_id' => $findSuccessStatus->id
            ]);
            $this->masterTotalService->incrementTotalSaldo($transaction->user_id, $transaction->amount);
            DB::commit();           

            $newTransaction = $this->findByExternalId($externalId);

            broadcast(new PaymentSuccess($newTransaction))->toOthers();

            return $updateTransaction;
        }
        catch(\Exception $e ) {
            dd($e);
            DB::rollBack();
            return $this->responseService->internalServer($e);
        }
    }

    public function updateStatusFailed($externalId)
    {
        DB::beginTransaction();
        try {
            $transaction =  $this->findByExternalId($externalId);
            $findSuccessStatus =  $this->masterStatusService->findByCode('failed');
            $this->transactionRepository->update($transaction, $findSuccessStatus->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->responseService->internalServer($e);
        }
    }

}