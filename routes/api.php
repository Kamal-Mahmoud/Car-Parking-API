<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ZoneController;
use App\Http\Controllers\Api\V1\ParkingController;
use App\Http\Controllers\Api\V1\VehicleController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\ProfileController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Auth\PasswordUpdateController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("auth/register", RegisterController::class);
Route::post('auth/login', LoginController::class);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [ProfileController::class, 'show']);
    Route::put('profile', [ProfileController::class, 'update']);
    Route::put("password", PasswordUpdateController::class);
    Route::post('auth/logout', LogoutController::class);
});
Route::middleware('auth:sanctum')->group(function () {
    // ... profile routes
 
    Route::apiResource('vehicles', VehicleController::class);
});

Route::get('zones', [ZoneController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    // ... profile and vehicles
    Route::get('parkings/{parking}', [ParkingController::class, 'show']);
    Route::post('parkings/start', [ParkingController::class, 'start']);
    Route::put('parkings/{parking}', [ParkingController::class, 'stop']);
});