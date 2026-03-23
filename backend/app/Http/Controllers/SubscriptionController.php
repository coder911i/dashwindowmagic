<?php

namespace App\Http\Controllers;

use App\Services\SubscriptionService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    use ApiResponse;

    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function createOrder(Request $request)
    {
        $tenantId = $request->attributes->get('tenantId');
        $order = $this->subscriptionService->createOrder($tenantId, $request->input('planKey'));
        
        return $this->success([
            'orderId' => $order['id'],
            'amount' => $order['amount'],
            'key' => config('services.razorpay.key')
        ]);
    }

    public function verifyPayment(Request $request)
    {
        $tenantId = $request->attributes->get('tenantId');
        
        // In production, verify Razorpay signature here
        $this->subscriptionService->updateSubscription($tenantId, $request->all());
        
        return $this->success(null, 'Subscription updated successfully.');
    }

    public function current(Request $request)
    {
        $tenantId = $request->attributes->get('tenantId');
        $sub = $this->subscriptionService->getSubscription($tenantId);
        
        return $this->success($sub);
    }
}
