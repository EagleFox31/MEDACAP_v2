<?php

namespace Infrastructure\Mongo;

use Domain\Repository\FunctionalGroupRepositoryInterface;
use Domain\Model\FunctionalGroup;
use MongoDB\BSON\ObjectId;

class FunctionalGroupRepository implements FunctionalGroupRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('functionalGroups');
    }

    public function findById($id): ?FunctionalGroup
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

    public function save(FunctionalGroup $group): void
    {
        $data = ['name' => $group->getName()];
        if ($group->getId()) {
            $this->collection->updateOne(['_id' => new ObjectId($group->getId())], ['$set' => $data]);
        } else {
            $this->collection->insertOne($data);
        }
    }

    public function delete($id): void
    {
        $this->collection->deleteOne(['_id' => new ObjectId($id)]);
    }

    private function map($doc): FunctionalGroup
    {
        return new FunctionalGroup((string)$doc['_id'], $doc['name']);
    }
}
