<?php

namespace App\Repositories;

class EnquiryRepository extends BaseRepository
{
    protected string $collection = 'enquiries';

    public function getFiltered(array $filters)
    {
        $query = $this->getCollection();

        if (isset($filters['source'])) {
            $query = $query->where('source', '==', $filters['source']);
        }

        if (isset($filters['status'])) {
            $query = $query->where('status', '==', $filters['status']);
        }

        if (isset($filters['agentId'])) {
            $query = $query->where('agentId', '==', $filters['agentId']);
        }

        $documents = $query->orderBy('createdAt', 'desc')->documents();
        
        $enquiries = [];
        foreach ($documents as $doc) {
            if ($doc->exists()) {
                $enquiries[] = array_merge(['id' => $doc->id()], $doc->data());
            }
        }
        return $enquiries;
    }

    public function findByPhone(string $phone)
    {
        return $this->getCollection()
            ->where('phone', '==', $phone)
            ->limit(1)
            ->documents();
    }
}
