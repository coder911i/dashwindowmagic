<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Kreait\Laravel\Firebase\Facades\Firebase;

class WhatsAppService
{
    protected $baseUrl;
    protected $token;
    protected $phoneId;

    public function __construct()
    {
        $this->token = config('whatsapp.access_token');
        $this->phoneId = config('whatsapp.phone_number_id');
        $this->baseUrl = "https://graph.facebook.com/" . config('whatsapp.api_version') . "/" . $this->phoneId;
    }

    public function sendMessage(string $tenantId, string $to, string $message)
    {
        // Ensure +91 format
        $to = str_starts_with($to, '+') ? substr($to, 1) : $to;
        if (!str_starts_with($to, '91')) $to = '91' . $to;

        $response = Http::withToken($this->token)->post($this->baseUrl . "/messages", [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'text',
            'text' => ['body' => $message]
        ]);

        $data = $response->json();
        $messageId = $data['messages'][0]['id'] ?? null;

        if ($messageId) {
            $this->logMessage($tenantId, $to, $message, $messageId);
        }

        return $data;
    }

    public function logMessage(string $tenantId, string $to, string $message, string $messageId)
    {
        Firebase::firestore()->database()
            ->collection('tenants')
            ->document($tenantId)
            ->collection('messages')
            ->document($messageId)
            ->set([
                'to' => $to,
                'content' => $message,
                'status' => 'sent',
                'type' => 'whatsapp',
                'createdAt' => new \DateTime()
            ]);
    }

    public function updateStatus(string $messageId, string $status)
    {
        // This is tricky because we don't know the tenantId from the webhook easily
        // In a real app, we'd store messageId -> tenantId mapping in a global collection
        // For now, we'll search or assume a structure
    }
}
