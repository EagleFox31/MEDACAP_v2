<?php

namespace Infrastructure\Mongo;

use Domain\Repository\ValidationThresholdRepositoryInterface;
use Domain\Model\ValidationThreshold;
use MongoDB\BSON\ObjectId;

class ValidationThresholdRepository implements ValidationThresholdRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('validationThresholds');
    }

    public function findById($id): ?ValidationThreshold
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

    public function save(ValidationThreshold $threshold): void
    {
        $data = [
            'level' => $threshold->getLevel(),
            'threshold' => $threshold->getThreshold(),
        ];
        if ($threshold->getId()) {
            $this->collection->updateOne(['_id' => new ObjectId($threshold->getId())], ['$set' => $data]);
        } else {
            $this->collection->insertOne($data);
        }
    }

    public function delete($id): void
    {
        $this->collection->deleteOne(['_id' => new ObjectId($id)]);
    }

    private function map($doc): ValidationThreshold
    {
        return new ValidationThreshold((string)$doc['_id'], $doc['level'], $doc['threshold']);
    }
}
