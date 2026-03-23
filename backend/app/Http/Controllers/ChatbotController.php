<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Kreait\Laravel\Firebase\Facades\Firebase;

class ChatbotController extends Controller
{
    use ApiResponse;

    public function message(Request $request)
    {
        $tenantId = $request->input('tenantId');
        $sessionId = $request->input('sessionId');
        
        // Proxy to FastAPI
        $response = Http::post(config('services.ai_service.url') . '/chatbot/message', [
            'tenantId' => $tenantId,
            'sessionId' => $sessionId,
            'message' => $request->input('message'),
            'conversationHistory' => $request->input('history', []),
            'visitorName' => $request->input('visitorName'),
            'visitorPhone' => $request->input('visitorPhone'),
        ]);

        $data = $response->json();

        // Log session in Firestore
        Firebase::firestore()->database()
            ->collection('tenants')
            ->document($tenantId)
            ->collection('chatbot_sessions')
            ->document($sessionId)
            ->set([
                'lastMessage' => $request->input('message'),
                'updatedAt' => new \DateTime(),
                'capturedLead' => $data['capturedLead'] ?? false
            ], ['merge' => true]);

        return $this->success($data);
    }

    public function createLead(Request $request)
    {
        $tenantId = $request->input('tenantId');
        
        $doc = Firebase::firestore()->database()
            ->collection('tenants')
            ->document($tenantId)
            ->collection('leads')
            ->newDocument();
            
        $doc->set([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'source' => 'chatbot',
            'status' => 'new',
            'createdAt' => new \DateTime(),
            'updatedAt' => new \DateTime(),
        ]);

        return $this->success(['id' => $doc->id()], 'Lead captured from chatbot.');
    }

    public function config(string $tenantId)
    {
        $doc = Firebase::firestore()->database()
            ->collection('tenants')
            ->document($tenantId)
            ->collection('chatbot_config')
            ->document('default')
            ->snapshot();

        $config = $doc->exists() ? $doc->data() : [
            'greeting' => 'Hi! How can I help you find your dream home today?',
            'primaryColor' => '#2563EB',
            'nameRequired' => true,
            'phoneRequired' => true
        ];

        return $this->success($config);
    }
}
