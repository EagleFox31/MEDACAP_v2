<?php

namespace Infrastructure\Mongo;

use Domain\Repository\AllocationRepositoryInterface;
use Domain\Model\Allocation;
use MongoDB\BSON\ObjectId;

class AllocationRepository implements AllocationRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('allocations');
    }

    public function save(Allocation $allocation): void
    {
        $data = [
            'userId' => $allocation->getUserId(),
            'quizId' => $allocation->getQuizId(),
            'level' => $allocation->getLevel(),
            'brand' => $allocation->getBrand(),
            'active' => $allocation->isActive(),
            'sessionStatus' => $allocation->getSessionStatus(),
        ];
        if ($allocation->getId()) {
            $this->collection->updateOne(['_id' => new ObjectId($allocation->getId())], ['$set' => $data]);
        } else {
            $this->collection->insertOne($data);
        }
    }

    public function findActiveByUser($userId): array
    {
        $cursor = $this->collection->find(['userId' => $userId, 'active' => true]);
        $result = [];
        foreach ($cursor as $doc) {
            $result[] = new Allocation(
                (string) $doc['_id'],
                $doc['userId'],
                $doc['quizId'],
                $doc['level'],
                $doc['brand'],
                $doc['active'],
                $doc['sessionStatus']
            );
        }
        return $result;
    }
}
