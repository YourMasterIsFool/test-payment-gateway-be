<?php
namespace App\Repository;

use App\Models\Paket;

class PaketRepository {
    public function findById($id): ?Paket {
        return Paket::find($id);
    }
}