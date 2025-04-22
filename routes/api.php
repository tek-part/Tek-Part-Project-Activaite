<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LicenseController;
use App\Http\Controllers\Api\SecureConnectionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// License Management API with middleware protection
// Route::middleware(['auth:sanctum', 'license.permission:license-api-access'])->group(function () {
    Route::prefix('license')->group(function () {
        Route::post('/validate', [LicenseController::class, 'validateLicense']);
        Route::post('/activate', [LicenseController::class, 'activate']);
        Route::post('/deactivate', [LicenseController::class, 'deactivate']);
        Route::post('/check', [LicenseController::class, 'check']);
        Route::post('/token/generate', [LicenseController::class, 'generateToken']);
        Route::post('/token/verify', [LicenseController::class, 'verifyToken']);
        Route::post('/check-domain', [LicenseController::class, 'checkDomainLicense']);
    });
// });

// Secure Connection API - complex and obfuscated connection mechanism
// No middleware protection as it implements its own security layers
Route::post('/secure-handshake', [SecureConnectionController::class, 'secureHandshake']);
Route::post('/secure-channel', [SecureConnectionController::class, 'secureChannel']);
