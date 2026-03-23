<?php

namespace App\Services;

use App\Repositories\BuilderRepository;

class BuilderService extends BaseService
{
    public function __construct(BuilderRepository $builderRepository)
    {
        $this->repository = $builderRepository;
    }

    public function getCommissions()
    {
        $documents = $this->repository->getCommissions();
        $commissions = [];
        foreach ($documents as $doc) {
            if ($doc->exists()) {
                $commissions[] = array_merge(['id' => $doc->id()], $doc->data());
            }
        }
        return $commissions;
    }

    public function logCommissionPayment(string $commissionId, array $paymentData)
    {
        // This logic is specific and needs careful tenant-scoping
        // Since getCommissions already uses $this->tenantId, we'll do the same here
        
        $ref = $this->repository->getDatabase()
            ->collection('tenants')
            ->document($this->repository->getTenantId()) // Accessing repo's tenantId
            ->collection('commissions')
            ->document($commissionId);

        $snap = $ref->snapshot();
        if (!$snap->exists()) return;

        $current = $snap->data();
        $history = $current['paymentHistory'] ?? [];
        $history[] = array_merge($paymentData, ['timestamp' => new \DateTime()]);
        
        $newPaidAmount = ($current['paidAmount'] ?? 0) + $paymentData['amount'];
        $status = $newPaidAmount >= $current['commissionAmount'] ? 'paid' : 'partial';

        $ref->set([
            'paidAmount' => $newPaidAmount,
            'status' => $status,
            'paymentHistory' => $history,
            'updatedAt' => new \DateTime()
        ], ['merge' => true]);
    }
}
