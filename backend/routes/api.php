<?php

use App\Http\Controllers\Auth\AcceptInviteController;
use App\Http\Controllers\Auth\InviteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LeadController;
use App\Http\Middleware\TenantMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json(['success' => true, 'message' => 'Watering CRM API is healthy']);
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/accept-invite', [AcceptInviteController::class, 'accept']);
});

Route::middleware([TenantMiddleware::class])->group(function () {
    
    Route::prefix('auth')->group(function () {
        Route::get('/me', [LoginController::class, 'me']);
        Route::post('/invite', [InviteController::class, 'invite']);
    });

    // Lead Management Routes
    Route::prefix('leads')->group(function () {
        Route::get('/', [LeadController::class, 'index']);
        Route::post('/', [LeadController::class, 'store']);
        Route::get('/{id}', [LeadController::class, 'show']);
        Route::put('/{id}', [LeadController::class, 'update']);
        Route::delete('/{id}', [LeadController::class, 'destroy']);
        Route::post('/{id}/score', [LeadController::class, 'score']);
    });
});

