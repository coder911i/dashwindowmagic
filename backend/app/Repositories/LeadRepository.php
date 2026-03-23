<?php

namespace App\Repositories;

class LeadRepository extends BaseRepository
{
    protected string $collection = 'leads';

    public function getByAgent(string $agentId)
    {
        $documents = $this->getCollection()
            ->where('agentId', '==', $agentId)
            ->documents();

            
        $data = [];
        foreach ($documents as $document) {
            if ($document->exists()) {
                $data[] = array_merge(['id' => $document->id()], $document->data());
            }
        }
        return $data;
    }

    public function addTimelineEvent(string $id, array $event)
    {
        $docRef = $this->getCollection()->document($id);
        $snapshot = $docRef->snapshot();
        
        if ($snapshot->exists()) {
            $timeline = $snapshot->get('timeline') ?? [];
            $timeline[] = $event;
            
            $docRef->update([
                ['path' => 'timeline', 'value' => $timeline],
                ['path' => 'updatedAt', 'value' => new \DateTime()]
            ]);
            
            return true;
        }
        return false;
    }

    public function bulkStore(array $records): int
    {
        $collection = $this->getCollection();
        $batchCount = 0;

        foreach ($records as $record) {
            $collection->add($record);
            $batchCount++;
        }

        return $batchCount;
    }
}
