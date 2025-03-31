<?php

namespace Database\Seeders;

use App\Models\TransactionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterTransactionType extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $data = [
            [
                'code' => 'deposit',
                'name' => 'Deposit',
            ],
            [
                'code' => 'withdraw',
                'name' => 'Withdraw',
            ],
        ];
        foreach ($data as $key => $value) {
            $findMaster =  TransactionType::where('code', $value['code'])->first();
            if (!$findMaster) {
                TransactionType::create($value);
            }
        }   
    }
}
