<?php

namespace App\Http\Controllers;

use App\Services\MessageService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    use ApiResponse;

    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function generate(Request $request)
    {
        $tenantId = $request->attributes->get('tenantId');
        
        try {
            $data = $request->all();
            $response = $this->messageService->generateWithAI($data, $tenantId);
            return $this->success($response);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function index(Request $request)
    {
        $tenantId = $request->attributes->get('tenantId');
        $leadId = $request->query('leadId');
        
        $messages = $this->messageService->getMessages($tenantId, $leadId);
        return $this->success($messages);
    }
}
