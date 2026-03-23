<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Kreait\Laravel\Firebase\Facades\Firebase;

class MessageService
{
    protected $aiServiceUrl;

    public function __construct()
    {
        $this->aiServiceUrl = env('AI_SERVICE_URL', 'http://localhost:8001');
    }

    public function generateWithAI(array $data, string $tenantId)
    {
        $response = Http::post("{$this->aiServiceUrl}/ai/generate-message", $data);

        if ($response->failed()) {
            throw new \Exception('AI Service failed to generate message.');
        }

        $result = $response->json();

        // Log the generation in Firestore
        if (isset($data['leadId'])) {
            $this->storeMessage($tenantId, [
                'leadId' => $data['leadId'],
                'message' => $result['message'],
                'type' => $data['messageType'],
                'status' => 'draft',
                'createdAt' => new \DateTime()
            ]);
        }

        return $result;
    }

    public function storeMessage(string $tenantId, array $data)
    {
        return Firebase::firestore()->database()
            ->collection('tenants')
            ->document($tenantId)
            ->collection('messages')
            ->newDocument()
            ->set($data);
    }

    public function getMessages(string $tenantId, ?string $leadId = null)
    {
        $query = Firebase::firestore()->database()
            ->collection('tenants')
            ->document($tenantId)
            ->collection('messages')
            ->orderBy('createdAt', 'desc');

        if ($leadId) {
            $query = $query->where('leadId', '==', $leadId);
        }

        $documents = $query->limit(20)->documents();
        $messages = [];
        foreach ($documents as $doc) {
            if ($doc->exists()) {
                $messages[] = array_merge(['id' => $doc->id()], $doc->data());
            }
        }
        return $messages;
    }
}
