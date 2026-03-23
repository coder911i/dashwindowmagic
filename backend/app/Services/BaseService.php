<?php

namespace App\Services;

class BaseService
{
    protected $repository;

    /**
     * Set the current tenant context in the repository.
     */
    public function setTenantContext(string $tenantId): void
    {
        $this->repository->setTenantId($tenantId);
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function getById(string $id)
    {
        return $this->repository->getById($id);
    }

    public function store(array $data)
    {
        return $this->repository->store($data);
    }

    public function update(string $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(string $id)
    {
        $this->repository->delete($id);
    }
}
