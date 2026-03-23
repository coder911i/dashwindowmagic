<?php

namespace App\Repositories;

use Kreait\Laravel\Firebase\Facades\Firebase;
use Google\Cloud\Firestore\FirestoreClient;

class BaseRepository
{
    protected string $collection;
    protected ?string $tenantId = null;

    /**
     * Set the current tenant context.
     */
    public function setTenantId(string $tenantId): void
    {
        $this->tenantId = $tenantId;
    }

    public function getTenantId(): ?string
    {
        return $this->tenantId;
    }

    /**
     * Get the firestore database instance.
     */
    protected function getDatabase(): FirestoreClient
    {
        return Firebase::firestore()->database();
    }

    /**
     * Get the collection for the current tenant.
     * All tenant data is nested under /tenants/{tenantId}/{collection}
     */
    protected function getCollection()
    {
        if (!$this->tenantId) {
            throw new \Exception('Tenant context not set in Repository.');
        }

        return $this->getDatabase()
            ->collection('tenants')
            ->document($this->tenantId)
            ->collection($this->collection);
    }

    public function getAll()
    {
        $documents = $this->getCollection()->documents();
        $data = [];
        foreach ($documents as $document) {
            if ($document->exists()) {
                $data[] = array_merge(['id' => $document->id()], $document->data());
            }
        }
        return $data;
    }

    public function getById(string $id)
    {
        $document = $this->getCollection()->document($id)->snapshot();
        if ($document->exists()) {
            return array_merge(['id' => $document->id()], $document->data());
        }
        return null;
    }

    public function store(array $data)
    {
        $docRef = $this->getCollection()->newDocument();
        $docRef->set($data);
        return array_merge(['id' => $docRef->id()], $data);
    }

    public function update(string $id, array $data)
    {
        $docRef = $this->getCollection()->document($id);
        $docRef->set($data, ['merge' => true]);
        return $this->getById($id);
    }

    public function delete(string $id)
    {
        $this->getCollection()->document($id)->delete();
    }
}
