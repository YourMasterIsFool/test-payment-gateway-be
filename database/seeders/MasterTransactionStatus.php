<?php

namespace Database\Seeders;

use App\Models\TransactionStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterTransactionStatus extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $data = [
            [
                'code' => 'pending',
                'name' => 'Pending',
            ],
            [
                'code' => 'success',
                'name' => 'Success',
            ],
            [
                'code' => 'failed',
                'name' => 'Failed',      
            ]
            ];
            

        foreach ($data as $key => $value) {
           $findMaster = TransactionStatus::where('code', $value['code'])->first();
           if(!$findMaster) {
            TransactionStatus::create($value);
           }    
        }   
    }
}
