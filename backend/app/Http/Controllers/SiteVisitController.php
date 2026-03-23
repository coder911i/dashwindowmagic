<?php

namespace App\Http\Controllers;

use App\Services\SiteVisitService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SiteVisitController extends Controller
{
    use ApiResponse;

    protected $visitService;

    public function __construct(SiteVisitService $visitService)
    {
        $this->visitService = $visitService;
    }

    public function index(Request $request)
    {
        $tenantId = $request->attributes->get('tenantId');
        $visits = $this->visitService->list($tenantId, $request->all());
        return $this->success($visits);
    }

    public function store(Request $request)
    {
        $tenantId = $request->attributes->get('tenantId');
        
        $validator = Validator::make($request->all(), [
            'leadId' => 'required|string',
            'leadName' => 'required|string',
            'visitDate' => 'required|date',
            'visitTime' => 'required|string',
            'propertyName' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $visit = $this->visitService->schedule($tenantId, $request->all());
        return $this->success($visit, 'Site visit scheduled.', 201);
    }

    public function update(Request $request, string $id)
    {
        $tenantId = $request->attributes->get('tenantId');
        $this->visitService->updateStatus($tenantId, $id, $request->status);
        return $this->success(null, 'Visit status updated.');
    }
}
