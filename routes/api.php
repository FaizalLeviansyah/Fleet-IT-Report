<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AssetSyncController;

// Pintu masuk rahasia untuk Agent Laptop (Metode POST)
Route::post('/v1/agent/sync-asset', [AssetSyncController::class, 'sync']);
