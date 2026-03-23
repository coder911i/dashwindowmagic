<?php

namespace App\Http\Controllers;

use App\Services\BuilderService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class BuilderController extends Controller
{
    use ApiResponse;

    protected $builderService;

    public function __construct(BuilderService $builderService)
    {
        $this->builderService = $builderService;
    }

    public function index(Request $request)
    {
        $tenantId = $request->attributes->get('tenantId');
        $this->builderService->setTenantContext($tenantId);
        return $this->success($this->builderService->getAll());
    }

    public function store(Request $request)
    {
        $tenantId = $request->attributes->get('tenantId');
        $this->builderService->setTenantContext($tenantId);
        $builder = $this->builderService->store($request->all());
        return $this->success($builder, 'Builder profile created.');
    }

    public function commissions(Request $request)
    {
        $tenantId = $request->attributes->get('tenantId');
        $this->builderService->setTenantContext($tenantId);
        return $this->success($this->builderService->getCommissions());
    }

    public function logPayment(Request $request, string $id)
    {
        $tenantId = $request->attributes->get('tenantId');
        $this->builderService->setTenantContext($tenantId);
        $this->builderService->logCommissionPayment($id, $request->all());
        return $this->success(null, 'Payment logged successfully.');
    }
}
