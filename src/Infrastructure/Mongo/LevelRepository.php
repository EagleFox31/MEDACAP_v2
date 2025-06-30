<?php

namespace Infrastructure\Mongo;

use Domain\Repository\LevelRepositoryInterface;
use Domain\Model\Level;
use MongoDB\BSON\ObjectId;

class LevelRepository implements LevelRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('levels');
    }

    public function findById($id): ?Level
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

    public function save(Level $level): void
    {
        $data = ['name' => $level->getName()];
        if ($level->getId()) {
            $this->collection->updateOne(['_id' => new ObjectId($level->getId())], ['$set' => $data]);
        } else {
            $this->collection->insertOne($data);
        }
    }

    public function delete($id): void
    {
        $this->collection->deleteOne(['_id' => new ObjectId($id)]);
    }

    private function map($doc): Level
    {
        return new Level((string)$doc['_id'], $doc['name']);
    }
}
