<?php

namespace App\Repositories;

class RERARepository extends BaseRepository
{
    protected string $collection = 'rera_records';

    public function getByTenant()
    {
        return $this->getCollection()
            ->orderBy('createdAt', 'desc')
            ->documents();
    }

    public function storeByTenant(array $data)
    {
        return $this->store($data);
    }
}
