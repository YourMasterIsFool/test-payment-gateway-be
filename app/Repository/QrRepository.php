<?php
namespace App\Repository;

use App\Models\TransactionQr;

class QrRepository {

    public function create($response, $expire_at) {
        $createdQr = new TransactionQr();
        $createdQr->reference_id =  $response['external_id'];
        $createdQr->qr_string =  $response['qr_string'];
        $createdQr->qr_id =  $response['id'];
        $createdQr->amount =  $response['amount'];
        $createdQr->currency =  "IDR";
        $createdQr->channel_code = "ID-DANA";
        $createdQr->expire_at =  $expire_at;
        $createdQr->business_id =  '';
        $createdQr->status =  $response['status'];
        $createdQr->save();
        return $createdQr;
    }

    public function findByExternalId($externalId) {
        return TransactionQr::where('reference_id', $externalId)->first();
    }
}