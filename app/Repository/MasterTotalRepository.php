<?php
namespace App\Repository;

use App\Models\TotalTransaction;

class MasterTotalRepository {
   
    public function updateTotalSaldo($id, float $amount) {
        return TotalTransaction::where('id', $id)->update([
            'total_saldo' => $amount,
        ]);
    }

    public function findMasterTotalByUser(int $userId) {
        return TotalTransaction::where('user_id', $userId)->first();
    }

    public function createTotal(int $userId) {
        return TotalTransaction::create([
            'user_id' => $userId,
            'total_saldo' => 0,
            'total_penarikan' => 0,
        ]);
    }

    public function updateTotalPenarikan(int $id, float $amount) {
        return TotalTransaction::where('id', $id)->update([
            'total_penarikan' => $amount,
        ]);
    }
}