<?php

namespace App\Repositories;

class PropertyRepository extends BaseRepository
{
    protected string $collection = 'properties';

    public function search(array $filters)
    {
        $query = $this->getCollection();

        if (isset($filters['type'])) {
            $query = $query->where('type', '==', $filters['type']);
        }
        if (isset($filters['locality'])) {
            $query = $query->where('locality', '==', $filters['locality']);
        }
        if (isset($filters['bhk'])) {
            $query = $query->where('bhk', '==', $filters['bhk']);
        }

        $documents = $query->documents();
        $results = [];
        foreach ($documents as $document) {
            $results[] = array_merge(['id' => $document->id()], $document->data());
        }
        return $results;
    }
}
