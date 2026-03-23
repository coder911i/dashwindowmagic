<?php

namespace App\Services;

use App\Repositories\SiteVisitRepository;

class SiteVisitService extends BaseService
{
    public function __construct(SiteVisitRepository $siteVisitRepository)
    {
        $this->repository = $siteVisitRepository;
    }

    public function schedule(string $tenantId, array $data): array
    {
        $this->setTenantContext($tenantId);
        $data['createdAt'] = new \DateTime();
        $data['status'] = $data['status'] ?? 'scheduled';
        return $this->store($data);
    }

    public function list(string $tenantId, array $filters = []): array
    {
        $this->setTenantContext($tenantId);
        $documents = $this->repository->getFiltered($filters);
        
        $visits = [];
        foreach ($documents as $doc) {
            if ($doc->exists()) {
                $visits[] = array_merge(['id' => $doc->id()], $doc->data());
            }
        }
        return $visits;
    }

    public function updateStatus(string $tenantId, string $id, string $status): bool
    {
        $this->setTenantContext($tenantId);
        $this->update($id, [
            'status' => $status,
            'updatedAt' => new \DateTime()
        ]);
        return true;
    }
}
