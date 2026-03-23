<?php

namespace App\Repositories;

class SiteVisitRepository extends BaseRepository
{
    protected string $collection = 'site_visits';

    public function getFiltered(array $filters)
    {
        $query = $this->getCollection();

        if (isset($filters['agentId'])) {
            $query = $query->where('agentId', '==', $filters['agentId']);
        }

        if (isset($filters['status'])) {
            $query = $query->where('status', '==', $filters['status']);
        }

        return $query->orderBy('visitDate', 'asc')->documents();
    }
}
