<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AgentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/sentinel/sync', [AgentController::class, 'syncData']);

Route::post('/cctv/receive-snapshot', [\App\Http\Controllers\Api\CctvReceiverController::class, 'receive']);
