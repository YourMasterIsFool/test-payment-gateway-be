<?php

namespace App\Repository;

use App\Models\TransactionType;

class TransactionTypeRepository
{
 
    public function findByName($name):? TransactionType {
        return TransactionType::where('name', $name)->first();
    }

    public function findByCode($code): ?TransactionType
    {
        return TransactionType::where('code', $code)->first();
    }

}