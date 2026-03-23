<?php

namespace App\Http\Controllers;

use App\Services\RERAService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\RERARepository;

class RERAController extends Controller
{
    use ApiResponse;

    protected $reraService;
    protected $reraRepository;

    public function __construct(RERAService $reraService, RERARepository $reraRepository)
    {
        $this->reraService = $reraService;
        $this->reraRepository = $reraRepository;
    }

    public function validate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'state' => 'required|string',
            'reraNumber' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $result = $this->reraService->validate($request->state, $request->reraNumber);
        return $this->success($result);
    }

    public function index(Request $request)
    {
        $tenantId = $request->attributes->get('tenantId');
        
        $documents = $this->reraRepository->getByTenant($tenantId);

        $records = [];
        foreach ($documents as $doc) {
            if ($doc->exists()) {
                $records[] = array_merge(['id' => $doc->id()], $doc->data());
            }
        }
        return $this->success($records);
    }

    public function store(Request $request)
    {
        $tenantId = $request->attributes->get('tenantId');
        
        $data = $request->all();
        $data['createdAt'] = new \DateTime();
        
        $id = $this->reraRepository->storeByTenant($tenantId, $data);
        
        return $this->success(['id' => $id], 'RERA record tracked.', 201);
    }
}
