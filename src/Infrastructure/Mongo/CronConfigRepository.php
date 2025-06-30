<?php

namespace Infrastructure\Mongo;

use Domain\Repository\CronConfigRepositoryInterface;
use Domain\Model\CronConfig;
use MongoDB\BSON\ObjectId;

class CronConfigRepository implements CronConfigRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('cronConfigs');
    }

    public function findById($id): ?CronConfig
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

    public function save(CronConfig $config): void
    {
        $data = [
            'name' => $config->getName(),
            'schedule' => $config->getSchedule(),
            'active' => $config->isActive(),
        ];
        if ($config->getId()) {
            $this->collection->updateOne(['_id' => new ObjectId($config->getId())], ['$set' => $data]);
        } else {
            $this->collection->insertOne($data);
        }
    }

    public function delete($id): void
    {
        $this->collection->deleteOne(['_id' => new ObjectId($id)]);
    }

    private function map($doc): CronConfig
    {
        return new CronConfig((string)$doc['_id'], $doc['name'], $doc['schedule'], $doc['active']);
    }
}
