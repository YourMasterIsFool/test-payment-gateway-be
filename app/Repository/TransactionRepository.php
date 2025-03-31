<?php
namespace App\Repository;

use App\Dto\CreateTransactionDto;
use App\Models\Transactions;
use App\Services\TransactionService;
use Ramsey\Uuid\Rfc4122\UuidV1;

class TransactionRepository {
    public function create(CreateTransactionDto $transactionDto) {
        $generateUuid =  UuidV1::uuid4();
        $savedTransaction =  new Transactions();
        $savedTransaction->uuid = $generateUuid;
        $savedTransaction->external_id = $transactionDto->external_id;
        $savedTransaction->user_id = $transactionDto->user_id;
        $savedTransaction->amount = $transactionDto->amount;
        $savedTransaction->transaction_type_id = $transactionDto->transaction_type_id;
        $savedTransaction->transaction_status_id = $transactionDto->transaction_status_id;
        $savedTransaction->timestamp = $transactionDto->timestamp;
        $savedTransaction->save();
        return $savedTransaction;
    }

    public function findAll($userId =  null) {
        $query =  Transactions::query();
        if($userId) {
            $query =  $query->where('user_id', $userId);
        }
        return $query->with(['status', 'type'])->get();
    }

    public function findByExternalId($externalId) {
        return Transactions::where('external_id', $externalId)->with(['status','type'])->first();
    }

    public function update(int $transactionId, $data) {
        return Transactions::where('id', $transactionId)->update($data);
    }


}