<?php

namespace App\Services;

use App\Repositories\EnquiryRepository;
use Exception;

class EnquiryService extends BaseService
{
    public function __construct(EnquiryRepository $enquiryRepository)
    {
        $this->repository = $enquiryRepository;
    }

    public function list(array $filters)
    {
        return $this->repository->getFiltered($filters);
    }

    public function create(array $data)
    {
        // Duplicate detection
        $existing = $this->repository->findByPhone($data['phone']);
        $data['isDuplicate'] = false;
        
        foreach ($existing as $doc) {
            if ($doc->exists()) {
                $data['isDuplicate'] = true;
                break;
            }
        }

        $data['status'] = $data['status'] ?? 'new';
        $data['createdAt'] = new \DateTime();
        
        return $this->repository->create($data);
    }

    public function bulkUpdate(array $ids, array $data)
    {
        foreach ($ids as $id) {
            $this->repository->update($id, $data);
        }
        return true;
    }
}
