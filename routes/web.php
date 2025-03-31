<?php

use App\Http\Controllers\DepositController;
use Illuminate\Support\Facades\Route;



Route::post('/deposit', [DepositController::class, 'deposit']);