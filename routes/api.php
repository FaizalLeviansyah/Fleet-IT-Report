<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AgentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Endpoint rahasia untuk menerima data dari PC kapal
Route::post('/sentinel/sync', [AgentController::class, 'syncData']);
