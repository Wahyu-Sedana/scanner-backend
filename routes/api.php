<?php

use App\Http\Controllers\Api\PasscodeController;
use App\Http\Controllers\Api\ScanHistoryController;
use Illuminate\Support\Facades\Route;

Route::post('/passcode/validate', [PasscodeController::class, 'validateCode']);

Route::get('/scan-history', [ScanHistoryController::class, 'index']);
Route::post('/scan-history', [ScanHistoryController::class, 'store']);
