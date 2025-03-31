<?php

namespace Database\Seeders;

use App\Models\Paket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterPaket extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'code' => 'paket1',
                'price' => 10000,
                'is_active' => true,
            ],
            [
                'code' => 'paket2',
                'price' => 25000,
                'is_active' => true,
            ],
            [
                'code' => 'paket3',
                'price' => 50000,
                'is_active' => true,
            ],
            [
                'code' => 'paket3',
                'price' => 100000,
                'is_active' => true,    
            ],

        ];
        foreach($data as $paket) {
            $findPaket =  Paket::where('code', $paket['code'])->first();
            if(!$findPaket) {
                Paket::create($paket);
            }
        }
        
    }
}
