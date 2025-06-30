<?php

namespace Infrastructure\Mongo;

use Domain\Repository\ManagerRepositoryInterface;
use Domain\Model\Manager;
use MongoDB\BSON\ObjectId;

class ManagerRepository implements ManagerRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('managers');
    }

    public function findById($id): ?Manager
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

    public function save(Manager $manager): void
    {
        $data = [
            'firstName' => $manager->getFirstName(),
            'lastName' => $manager->getLastName(),
            'email' => $manager->getEmail(),
        ];
        if ($manager->getId()) {
            $this->collection->updateOne(['_id' => new ObjectId($manager->getId())], ['$set' => $data]);
        } else {
            $this->collection->insertOne($data);
        }
    }

    public function delete($id): void
    {
        $this->collection->deleteOne(['_id' => new ObjectId($id)]);
    }

    private function map($doc): Manager
    {
        return new Manager((string)$doc['_id'], $doc['firstName'], $doc['lastName'], $doc['email']);
    }
}
