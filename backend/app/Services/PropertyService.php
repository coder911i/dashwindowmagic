<?php

namespace App\Services;

use App\Repositories\PropertyRepository;

class PropertyService extends BaseService
{
    public function __construct(PropertyRepository $propertyRepository)
    {
        $this->repository = $propertyRepository;
    }

    public function searchProperties(array $filters)
    {
        return $this->repository->search($filters);
    }
}
