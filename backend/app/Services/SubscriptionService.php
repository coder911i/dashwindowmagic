<?php

namespace App\Services;

use Kreait\Laravel\Firebase\Facades\Firebase;
use Razorpay\Api\Api;

class SubscriptionService
{
    protected $razorpay;

    public function __construct()
    {
        $this->razorpay = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));
    }

    public function createOrder(string $tenantId, string $planKey)
    {
        $plans = config('plans');
        $plan = $plans[$planKey];

        $order = $this->razorpay->order->create([
            'receipt' => 'sub_' . $tenantId . '_' . time(),
            'amount' => $plan['price'],
            'currency' => 'INR'
        ]);

        return $order;
    }

    public function updateSubscription(string $tenantId, array $data)
    {
        $plans = config('plans');
        $plan = $plans[$data['planKey']];

        Firebase::firestore()->database()
            ->collection('tenants')
            ->document($tenantId)
            ->collection('subscription')
            ->document('current')
            ->set([
                'plan' => $data['planKey'],
                'status' => 'active',
                'razorpayPaymentId' => $data['paymentId'],
                'currentPeriodEnd' => (new \DateTime())->modify('+1 month'),
                'usage' => [
                    'agents' => 0,
                    'leads' => 0,
                    'aiMessages' => 0
                ]
            ]);
    }

    public function getSubscription(string $tenantId)
    {
        $doc = Firebase::firestore()->database()
            ->collection('tenants')
            ->document($tenantId)
            ->collection('subscription')
            ->document('current')
            ->snapshot();

        if (!$doc->exists()) {
            return [
                'plan' => 'free',
                'status' => 'inactive',
                'usage' => ['agents' => 0, 'leads' => 0, 'aiMessages' => 0]
            ];
        }

        return $doc->data();
    }
}
