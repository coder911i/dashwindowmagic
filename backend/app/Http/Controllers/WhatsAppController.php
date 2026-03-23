<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{
    use ApiResponse;

    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function send(Request $request)
    {
        $tenantId = $request->attributes->get('tenantId');
        
        $request->validate([
            'phone' => 'required',
            'message' => 'required'
        ]);

        $result = $this->whatsappService->sendMessage(
            $tenantId, 
            $request->input('phone'), 
            $request->input('message')
        );

        return $this->success($result, 'WhatsApp message initiated.');
    }

    public function webhook(Request $request)
    {
        // Meta Webhook Verification
        if ($request->query('hub_mode') === 'subscribe') {
            return response($request->query('hub_challenge'));
        }

        // Handle delivery receipts
        $body = $request->all();
        if (isset($body['entry'][0]['changes'][0]['value']['statuses'][0])) {
            $statusData = $body['entry'][0]['changes'][0]['value']['statuses'][0];
            // Update Firestore status
        }

        return response('OK');
    }
}
