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

    public function scoreLead(string $leadId)
    {
        $score = rand(1, 10);
        $this->repository->update($leadId, ['score' => $score]);
        return $score;
    }
}
