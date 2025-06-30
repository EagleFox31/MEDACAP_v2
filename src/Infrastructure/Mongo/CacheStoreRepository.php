<?php

namespace Infrastructure\Mongo;

use Domain\Repository\CacheStoreRepositoryInterface;
use Domain\Model\CacheStore;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class CacheStoreRepository implements CacheStoreRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('cacheStore');
    }

    public function findById($id): ?CacheStore
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

    public function save(CacheStore $entry): void
    {
        $data = [
            'key' => $entry->getKey(),
            'value' => $entry->getValue(),
            'expiresAt' => $entry->getExpiresAt() ? new UTCDateTime($entry->getExpiresAt()) : null,
        ];
        if ($entry->getId()) {
            $this->collection->updateOne(['_id' => new ObjectId($entry->getId())], ['$set' => $data]);
        } else {
            $this->collection->insertOne($data);
        }
    }

    public function delete($id): void
    {
        $this->collection->deleteOne(['_id' => new ObjectId($id)]);
    }

    private function map($doc): CacheStore
    {
        $expiresAt = isset($doc['expiresAt']) && $doc['expiresAt'] instanceof UTCDateTime
            ? $doc['expiresAt']->toDateTime()
            : null;
        return new CacheStore((string)$doc['_id'], $doc['key'], $doc['value'], $expiresAt);
    }
}
