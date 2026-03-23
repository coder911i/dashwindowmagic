<?php

namespace App\Http\Controllers;

use App\Services\PropertyService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    use ApiResponse;

    protected $propertyService;

    public function __construct(PropertyService $propertyService)
    {
        $this->propertyService = $propertyService;
    }

    public function index(Request $request)
    {
        $tenantId = $request->attributes->get('tenantId');
        $this->propertyService->setTenantContext($tenantId);
        
        $properties = $this->propertyService->getAll();
        return $this->success($properties);
    }

    public function store(Request $request)
    {
        $tenantId = $request->attributes->get('tenantId');
        $this->propertyService->setTenantContext($tenantId);

        $data = $request->all();
        $data['tenantId'] = $tenantId;
        $data['createdAt'] = new \DateTime();
        $data['updatedAt'] = new \DateTime();

        $property = $this->propertyService->store($data);
        return $this->success($property, 'Property added to inventory.', 201);
    }

    public function update(Request $request, string $id)
    {
        $tenantId = $request->attributes->get('tenantId');
        $this->propertyService->setTenantContext($tenantId);

        $property = $this->propertyService->update($id, $request->all());
        return $this->success($property, 'Property updated.');
    }

    public function destroy(Request $request, string $id)
    {
        $tenantId = $request->attributes->get('tenantId');
        $this->propertyService->setTenantContext($tenantId);

        $this->propertyService->delete($id);
        return $this->success(null, 'Property removed.');
    }

    public function search(Request $request)
    {
        $tenantId = $request->attributes->get('tenantId');
        $this->propertyService->setTenantContext($tenantId);

        $results = $this->propertyService->searchProperties($request->all());
        return $this->success($results);
    }
}
