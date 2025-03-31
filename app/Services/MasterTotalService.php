<?php
namespace App\Services;

use App\Repository\MasterTotalRepository;

class MasterTotalService
{
    public function __construct(
        public MasterTotalRepository  $masterTotalRepository,
        public ResponseServices  $responseServices, 
    ){}

    public function incrementTotalSaldo(int $userId, float $amount)
    {
        
        $findTotal =  $this->masterTotalRepository->findMasterTotalByUser($userId);
        if(!$findTotal) {
            $this->masterTotalRepository->createTotal($userId);
        }

        $findTotal =  $this->masterTotalRepository->findMasterTotalByUser($userId);

        $calculateTotal =  $findTotal->total_saldo + $amount;
        try {
            $createdTotal =  $this->masterTotalRepository->updateTotalSaldo($findTotal->id, $calculateTotal);
            return $createdTotal;
        }
        catch(\Exception $e) {
            return $this->responseServices->internalServer($e);
        }   
    }

    public function decrementTotalSaldo(int $userId, float $amount)
    {
        $findTotal =  $this->masterTotalRepository->findMasterTotalByUser($userId);
        if (!$findTotal) {
            $this->masterTotalRepository->createTotal($userId);
        }
       
        $findTotal =  $this->masterTotalRepository->findMasterTotalByUser($userId);

        if($findTotal->total_saldo < $amount) {
            return $this->responseServices->badRequestResponse(null, 'Saldo tidak mencukupi');
        }

        $calculateTotal = (int) $findTotal->total_saldo -  (int) $amount;

        try {
            $createdTotal =  $this->masterTotalRepository->updateTotalSaldo($findTotal->id, $calculateTotal);
            return $createdTotal;
        } catch (\Exception $e) {
            return $this->responseServices->internalServer($e);
        }
    }
}