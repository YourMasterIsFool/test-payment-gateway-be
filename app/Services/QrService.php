<?php
namespace App\Services;

use App\Dto\CreateQrDto;
use App\Models\TransactionQr;
use App\Repository\QrRepository;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Illuminate\Support\Facades\Http;
use Illuminate\Container\Attributes\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB as FacadesDB;

class QrService {

    public function __construct(
        public ResponseServices $response_services,
        public QrRepository $qr_repository
    ) {
        
    }
    public function createQrCodeXendit(CreateQrDto $transaction_qr) {
        FacadesDB::beginTransaction();
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode(env('XENDIT_WRITE_API_KEY') . ':'),
                'Content-Type' => 'application/json',
            ])->post(env('XENDIT_API_URL') . '/qr_codes', $transaction_qr);
            if($response->failed()) {
                return $this->response_services->internalServer(null, 'Error when created qr code');
            }
            $createdQr =  $this->qr_repository->create($response, $transaction_qr->expire_at);
            FacadesDB::commit();
            return $createdQr;

            
        }
        catch(\Exception $e) {
        
            FacadesDB::rollBack();
            return $this->response_services->internalServer(null, 'Error when created qr code');
        }
    }


    public function generateQrCode($qrCodeData)
    {
        $qrCode = new QrCode(
            data: $qrCodeData,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            foregroundColor: new Color(0, 0, 0), // Hitam
            backgroundColor: new Color(255, 255, 255) // Putih
        );

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Convert QR Code to Base64
        $base64 = base64_encode($result->getString());

        return 'data:image/png;base64,' . $base64;
    }


    public function findByExternalId($orderId) {
        $find =  $this->qr_repository->findByExternalId($orderId);
        if(!$find) {
            return $this->response_services->notFound('Qr Code not found');
        }

        return $find;
    }

    public function generateQrCodeByExternalId($orderId) {
        $qr =  $this->qr_repository->findByExternalId($orderId);
        $qrCode =  $this->generateQrCode($qr->qr_string);

        return $qrCode;
    }
    public function simulate($orderId) {
     
    
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode(env('XENDIT_WRITE_API_KEY') . ':'),
                'Content-Type' => 'application/json',
            ])->post(env('XENDIT_API_URL') . '/qr_codes/$orderId/payments/simulate');
            if ($response->failed()) {
                dd($response->json());
                return $this->response_services->internalServer(null, 'Error when simulate qr code');
            }
            return $response;
       
    }

  
}