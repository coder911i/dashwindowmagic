<?php

namespace App\Services;

use App\Repositories\LeadRepository;

class LeadService extends BaseService
{
    public function __construct(LeadRepository $leadRepository)
    {
        $this->repository = $leadRepository;
    }

    public function getLeadsForAgent(string $agentId)
    {
        return $this->repository->getByAgent($agentId);
    }

    public function recordActivity(string $leadId, string $action, string $note, string $agentId)
    {
        return $this->repository->addTimelineEvent($leadId, [
            'action' => $action,
            'note' => $note,
            'agentId' => $agentId,
            'timestamp' => new \DateTime()
        ]);
    }

    public function updateStatus(string $leadId, string $status, string $agentId)
    {
        $this->repository->update($leadId, [
            'status' => $status,
            'updatedAt' => new \DateTime()
        ]);

        $this->recordActivity($leadId, 'status_change', "Status changed to $status", $agentId);
    }

    public function scoreLead(string $leadId)
    {
        $score = rand(1, 10);
        $this->repository->update($leadId, ['score' => $score]);
        return $score;
    }

    public function importLeads(array $leads, string $agentId)
    {
        $processedLeads = [];
        $now = new \DateTime();

        foreach ($leads as $lead) {
            $processedLeads[] = array_merge($lead, [
                'agentId' => $agentId,
                'status' => 'new',
                'createdAt' => $now,
                'updatedAt' => $now,
                'timeline' => [[
                    'action' => 'imported',
                    'note' => 'Lead imported via CSV',
                    'agentId' => $agentId,
                    'timestamp' => $now
                ]]
            ]);
        }

        return $this->repository->bulkStore($processedLeads);
    }
}
