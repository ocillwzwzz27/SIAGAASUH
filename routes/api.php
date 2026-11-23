<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorController;

// IoT API Routes (tidak perlu auth cookie, pakai device_token)
Route::prefix('v1')->name('api.')->group(function () {
    Route::post('/sensor', [SensorController::class, 'store'])->name('sensor.store');
    Route::get('/session/active', [SensorController::class, 'getActiveSession'])->name('session.active');
});
