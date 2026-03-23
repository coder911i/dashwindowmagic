<?php

namespace App\Http\Controllers;

use App\Services\EnquiryService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnquiryController extends Controller
{
    use ApiResponse;

    protected $enquiryService;

    public function __construct(EnquiryService $enquiryService)
    {
        $this->enquiryService = $enquiryService;
    }

    public function index(Request $request)
    {
        $tenantId = $request->attributes->get('tenantId');
        $this->enquiryService->setTenantContext($tenantId);

        $enquiries = $this->enquiryService->list($request->all());
        return $this->success($enquiries);
    }

    public function store(Request $request)
    {
        $tenantId = $request->attributes->get('tenantId');
        $this->enquiryService->setTenantContext($tenantId);

        $validator = Validator::make($request->all(), [
            'leadName' => 'required|string',
            'phone' => 'required|string',
            'source' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $data = $request->all();
        $data['tenantId'] = $tenantId;
        
        $enquiry = $this->enquiryService->create($data);
        return $this->success($enquiry, 'Enquiry added successfully.', 201);
    }

    public function update(Request $request, string $id)
    {
        $tenantId = $request->attributes->get('tenantId');
        $this->enquiryService->setTenantContext($tenantId);

        $enquiry = $this->enquiryService->update($id, $request->all());
        return $this->success($enquiry, 'Enquiry updated successfully.');
    }

    public function bulk(Request $request)
    {
        $tenantId = $request->attributes->get('tenantId');
        $this->enquiryService->setTenantContext($tenantId);

        $ids = $request->input('ids', []);
        $data = $request->input('data', []);

        $this->enquiryService->bulkUpdate($ids, $data);
        return $this->success(null, 'Bulk operation completed.');
    }
}
