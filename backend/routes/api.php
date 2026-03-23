<?php

use App\Http\Middleware\TenantMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/health', function () {
    return response()->json(['success' => true, 'message' => 'Watering CRM API is healthy']);
});

Route::middleware([TenantMiddleware::class])->group(function () {
    
    // Auth related info
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => [
                'firebase_uid' => $request->attributes->get('firebase_user_id'),
                'tenant_id' => $request->attributes->get('tenantId'),
                'role' => $request->attributes->get('role'),
            ]
        ]);
    });

    // Leads
    Route::prefix('leads')->group(function () {
        // Route::get('/', [LeadController::class, 'index']);
        // Route::post('/', [LeadController::class, 'store']);
        // Route::get('/{id}', [LeadController::class, 'show']);
        // Route::put('/{id}', [LeadController::class, 'update']);
        // Route::delete('/{id}', [LeadController::class, 'destroy']);
    });

    // Properties
    Route::prefix('properties')->group(function () {
        // Route::get('/', [PropertyController::class, 'index']);
    });

    // AI Proxy (To Python FastAPI Service)
    Route::prefix('ai')->group(function () {
        // Routes proxied to ai-service
    });
});
