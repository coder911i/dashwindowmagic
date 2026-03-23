<?php

namespace App\Repositories;

use Kreait\Laravel\Firebase\Facades\Firebase;
use Google\Cloud\Firestore\FirestoreClient;

abstract class BaseRepository
{
    protected FirestoreClient $firestore;
    protected string $collection;
    protected string $tenantId;

    public function __construct()
    {
        $this->firestore = Firebase::firestore()->database();
    }

    /**
     * Set the tenant context for queries.
     */
    public function setTenantId(string $tenantId): void
    {
        $this->tenantId = $tenantId;
    }

    /**
     * Get the collection reference for the current tenant.
     */
    protected function getCollection()
    {
        return $this->firestore->collection('tenants')->document($this->tenantId)->collection($this->collection);
    }

    public function all()
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

    public function find(string $id)
    {
        $document = $this->getCollection()->document($id)->snapshot();
        if ($document->exists()) {
            return array_merge(['id' => $document->id()], $document->data());
        }
        return null;
    }

    public function create(array $data)
    {
        $newDoc = $this->getCollection()->newDocument();
        $newDoc->set($data);
        return array_merge(['id' => $newDoc->id()], $data);
    }

    public function update(string $id, array $data)
    {
        $docRef = $this->getCollection()->document($id);
        $docRef->update($this->formatUpdateData($data));
        return $this->find($id);
    }

    public function delete(string $id)
    {
        $this->getCollection()->document($id)->delete();
        return true;
    }

    protected function formatUpdateData(array $data): array
    {
        $formatted = [];
        foreach ($data as $key => $value) {
            $formatted[] = ['path' => $key, 'value' => $value];
        }
        return $formatted;
    }
}
