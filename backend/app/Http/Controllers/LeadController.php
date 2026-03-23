<?php

namespace App\Http\Controllers;

use App\Services\LeadService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    use ApiResponse;

    protected $leadService;

    public function __construct(LeadService $leadService)
    {
        $this->leadService = $leadService;
    }

    public function index(Request $request)
    {
        $tenantId = $request->attributes->get('tenantId');
        $this->leadService->setTenantContext($tenantId);

        if ($request->attributes->get('role') === 'admin') {
            $leads = $this->leadService->getAll();
        } else {
            $agentId = $request->attributes->get('firebase_user_id');
            $leads = $this->leadService->getLeadsForAgent($agentId);
        }

        return $this->success($leads);
    }

    public function store(Request $request)
    {
        $tenantId = $request->attributes->get('tenantId');
        $this->leadService->setTenantContext($tenantId);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required|string',
            'budgetMin' => 'required|numeric',
            'budgetMax' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $data = $request->all();
        $data['tenantId'] = $tenantId;
        $data['agentId'] = $request->attributes->get('firebase_user_id');
        $data['status'] = 'new';
        $data['createdAt'] = new \DateTime();
        $data['updatedAt'] = new \DateTime();
        $data['timeline'] = [[
            'action' => 'created',
            'note' => 'Lead added to CRM',
            'agentId' => $data['agentId'],
            'timestamp' => new \DateTime()
        ]];

        $lead = $this->leadService->store($data);
        return $this->success($lead, 'Lead created successfully.', 201);
    }

    public function show(Request $request, string $id)
    {
        $tenantId = $request->attributes->get('tenantId');
        $this->leadService->setTenantContext($tenantId);
        
        $lead = $this->leadService->getById($id);
        if (!$lead) return $this->error('Lead not found.', 404);
        
        return $this->success($lead);
    }

    public function update(Request $request, string $id)
    {
        $tenantId = $request->attributes->get('tenantId');
        $this->leadService->setTenantContext($tenantId);

        $lead = $this->leadService->update($id, $request->all());
        return $this->success($lead, 'Lead updated successfully.');
    }

    public function destroy(Request $request, string $id)
    {
        $tenantId = $request->attributes->get('tenantId');
        $this->leadService->setTenantContext($tenantId);

        $this->leadService->delete($id);
        return $this->success(null, 'Lead deleted successfully.');
    }

    public function updateStatus(Request $request, string $id)
    {
        $tenantId = $request->attributes->get('tenantId');
        $agentId = $request->attributes->get('firebase_user_id');
        $this->leadService->setTenantContext($tenantId);

        $validator = Validator::make($request->all(), [
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $this->leadService->updateStatus($id, $request->status, $agentId);
        return $this->success(null, 'Lead status updated.');
    }

    public function score(Request $request, string $id)
    {
        $tenantId = $request->attributes->get('tenantId');
        $this->leadService->setTenantContext($tenantId);

        $score = $this->leadService->scoreLead($id);
        return $this->success(['score' => $score], 'Lead scored successfully.');
    }
}
