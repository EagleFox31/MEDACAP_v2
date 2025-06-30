<?php

namespace Infrastructure\Mongo;

use Domain\Repository\ProfessionalTaskRepositoryInterface;
use Domain\Model\ProfessionalTask;
use MongoDB\BSON\ObjectId;

class ProfessionalTaskRepository implements ProfessionalTaskRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('professionalTasks');
    }

    public function findById($id): ?ProfessionalTask
    {
        $doc = $this->collection->findOne(['_id' => new ObjectId($id)]);
        return $doc ? $this->map($doc) : null;
    }

    public function findAll(): array
    {
        $cursor = $this->collection->find();
        $result = [];
        foreach ($cursor as $doc) {
            $result[] = $this->map($doc);
        }
        return $result;
    }

    public function save(ProfessionalTask $task): void
    {
        $data = [
            'code' => $task->getCode(),
            'label' => $task->getLabel(),
            'description' => $task->getDescription(),
            'groupId' => $task->getGroupId(),
            'tags' => $task->getTags(),
            'active' => $task->isActive(),
            'createdAt' => $task->getCreatedAt(),
            'updatedAt' => $task->getUpdatedAt(),
            'createdBy' => $task->getCreatedBy(),
            'statistics' => $task->getStatistics(),
        ];
        if ($task->getId()) {
            $this->collection->updateOne(['_id' => new ObjectId($task->getId())], ['$set' => $data]);
        } else {
            $this->collection->insertOne($data);
        }
    }

    public function delete($id): void
    {
        $this->collection->deleteOne(['_id' => new ObjectId($id)]);
    }

    private function map($doc): ProfessionalTask
    {
        $createdAt = $doc['createdAt'] instanceof \MongoDB\BSON\UTCDateTime
            ? $doc['createdAt']->toDateTime()
            : new \DateTime();
        $updatedAt = isset($doc['updatedAt']) && $doc['updatedAt'] instanceof \MongoDB\BSON\UTCDateTime
            ? $doc['updatedAt']->toDateTime()
            : null;

        return new ProfessionalTask(
            (string) $doc['_id'],
            $doc['code'],
            $doc['label'],
            $doc['description'] ?? '',
            $doc['groupId'] ?? null,
            $doc['tags'] ?? [],
            $doc['active'] ?? true,
            $createdAt,
            $updatedAt,
            $doc['createdBy'] ?? null,
            $doc['statistics'] ?? []
        );
    }
}
