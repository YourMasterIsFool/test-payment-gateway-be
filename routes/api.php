<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\XenditController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');



Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::post('/xendit/callback', [XenditController::class, 'callback']);

Route::middleware('auth:sanctum')->group(function() {
    Route::post('/deposit', [DepositController::class, 'deposit']);
    Route::post('/withdraw', [WithdrawController::class, 'withdraw']);
    Route::get('/transaction/{orderId}/payment', [TransactionController::class, 'findOne']);
    Route::post('/transaction/{order_id}/simulate/qr_code', [TransactionController::class, 'simulateDeposit']);
    Route::get('/transaction/list', [TransactionController::class, 'findAll']);
});