<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BiometricApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Biometric Device Integration APIs (Hardware REST API & Cloud Push)
Route::group(['prefix' => 'biometric'], function () {
    Route::post('/device/register', [BiometricApiController::class, 'registerDevice']);
    Route::post('/device/heartbeat', [BiometricApiController::class, 'heartbeat']);
    Route::post('/attendance-events', [BiometricApiController::class, 'receiveAttendanceEvent']);
    Route::get('/devices', [BiometricApiController::class, 'listDevices']);
    Route::get('/devices/{id}/status', [BiometricApiController::class, 'deviceStatus']);
    Route::post('/devices/{id}/sync', [BiometricApiController::class, 'syncDevice']);
    Route::post('/employees/{id}/enroll', [BiometricApiController::class, 'enrollEmployee']);
    Route::post('/employees/{id}/remove', [BiometricApiController::class, 'removeBiometric']);
});

// Attendance Data Query APIs
Route::group(['prefix' => 'attendance'], function () {
    Route::get('/today', [BiometricApiController::class, 'todayAttendance']);
});
