<?php

namespace App\Services;

abstract class BaseService
{
    protected $repository;

    /**
     * Set the tenant context for the service and its repository.
     */
    public function setTenantContext(string $tenantId): void
    {
        if (method_exists($this->repository, 'setTenantId')) {
            $this->repository->setTenantId($tenantId);
        }
    }

    public function getAll()
    {
        return $this->repository->all();
    }

    public function getById(string $id)
    {
        return $this->repository->find($id);
    }

    public function store(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(string $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(string $id)
    {
        return $this->repository->delete($id);
    }
}
