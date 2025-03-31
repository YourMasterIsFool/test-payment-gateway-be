<?php
namespace App\Services;

use App\Repository\PaketRepository;

class PaketService {
    protected $paketRepo;
    protected $responseService;

    public function __construct(PaketRepository $paketRepo, ResponseServices $responseService) {
        $this->paketRepo =  $paketRepo;
        $this->responseService =  $responseService;
    }

    public function findById($id) {
        $findPaket =  $this->paketRepo->findById($id);

        if(!$findPaket) {
            return $this->responseService->notFound();
        }

        return $this->responseService->successResponse($findPaket);
    }
}