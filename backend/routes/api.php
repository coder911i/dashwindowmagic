<?php

use App\Http\Controllers\Auth\AcceptInviteController;
use App\Http\Controllers\Auth\InviteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\RERAController;
use App\Http\Controllers\SiteVisitController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\BuilderController;
use App\Http\Controllers\NRIController;
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
        Route::patch('/{id}/status', [LeadController::class, 'updateStatus']);
        Route::delete('/{id}', [LeadController::class, 'destroy']);
        Route::post('/{id}/score', [LeadController::class, 'score']);
    });

    // AI Message Routes
    Route::prefix('ai')->group(function () {
        Route::post('/generate-message', [MessageController::class, 'generate']);
        Route::get('/messages', [MessageController::class, 'index']);
    });

    // Enquiry Management Routes
    Route::prefix('enquiries')->group(function () {
        Route::get('/', [EnquiryController::class, 'index']);
        Route::post('/', [EnquiryController::class, 'store']);
        Route::put('/{id}', [EnquiryController::class, 'update']);
        Route::post('/bulk', [EnquiryController::class, 'bulk']);
    });

    // RERA Compliance Routes
    Route::prefix('rera')->group(function () {
        Route::get('/', [RERAController::class, 'index']);
        Route::post('/', [RERAController::class, 'store']);
        Route::post('/validate', [RERAController::class, 'validate']);
    });

    // Site Visit Routes
    Route::prefix('visits')->group(function () {
        Route::get('/', [SiteVisitController::class, 'index']);
        Route::post('/', [SiteVisitController::class, 'store']);
        Route::put('/{id}', [SiteVisitController::class, 'update']);
    });

    // Chatbot Routes (Public Proxy)
    Route::prefix('chatbot')->group(function () {
        Route::post('/message', [ChatbotController::class, 'message']);
        Route::post('/lead', [ChatbotController::class, 'createLead']);
        Route::get('/config/{tenantId}', [ChatbotController::class, 'config']);
    });

    // Subscription Routes
    Route::prefix('subscription')->group(function () {
        Route::get('/current', [SubscriptionController::class, 'current']);
        Route::post('/create-order', [SubscriptionController::class, 'createOrder']);
        Route::post('/verify-payment', [SubscriptionController::class, 'verifyPayment']);
    });

    // WhatsApp Routes
    Route::prefix('whatsapp')->group(function () {
        Route::post('/send', [WhatsAppController::class, 'send']);
        Route::any('/webhook', [WhatsAppController::class, 'webhook']);
    });

    // Property Routes
    Route::prefix('properties')->group(function () {
        Route::get('/', [PropertyController::class, 'index']);
        Route::get('/search', [PropertyController::class, 'search']);
        Route::post('/', [PropertyController::class, 'store']);
        Route::put('/{id}', [PropertyController::class, 'update']);
        Route::delete('/{id}', [PropertyController::class, 'destroy']);
    });

    // Builder & Commission Routes
    Route::prefix('builders')->group(function () {
        Route::get('/', [BuilderController::class, 'index']);
        Route::post('/', [BuilderController::class, 'store']);
    });

    Route::prefix('commissions')->group(function () {
        Route::get('/', [BuilderController::class, 'commissions']);
        Route::post('/{id}/payment', [BuilderController::class, 'logPayment']);
    });

    // NRI Module Routes
    Route::prefix('nri')->group(function () {
        Route::put('/leads/{id}', [NRIController::class, 'markAsNRI']);
        Route::get('/timezones', [NRIController::class, 'timezones']);
        Route::get('/currency-rates', [NRIController::class, 'currencyRates']);
    });
});
