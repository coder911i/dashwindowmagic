<?php

namespace App\Repositories;

class BuilderRepository extends BaseRepository
{
    protected string $collection = 'builders';

    public function getCommissions()
    {
        return $this->getDatabase()
            ->collection('tenants')
            ->document($this->tenantId)
            ->collection('commissions')
            ->documents();
    }
}
