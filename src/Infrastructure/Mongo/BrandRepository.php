<?php

namespace Infrastructure\Mongo;

use Domain\Repository\BrandRepositoryInterface;
use Domain\Model\Brand;
use MongoDB\BSON\ObjectId;

class BrandRepository implements BrandRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('brands');
    }

    public function findById($id): ?Brand
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

    public function save(Brand $brand): void
    {
        $data = ['name' => $brand->getName()];
        if ($brand->getId()) {
            $this->collection->updateOne(['_id' => new ObjectId($brand->getId())], ['$set' => $data]);
        } else {
            $this->collection->insertOne($data);
        }
    }

    public function delete($id): void
    {
        $this->collection->deleteOne(['_id' => new ObjectId($id)]);
    }

    private function map($doc): Brand
    {
        return new Brand((string)$doc['_id'], $doc['name']);
    }
}
