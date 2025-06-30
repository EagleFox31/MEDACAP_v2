<?php

namespace Infrastructure\Mongo;

use Domain\Repository\LogActionRepositoryInterface;
use Domain\Model\LogAction;
use MongoDB\BSON\ObjectId;

class LogActionRepository implements LogActionRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('logActions');
    }

    public function save(LogAction $log): void
    {
        $data = [
            'type' => $log->getType(),
            'userId' => $log->getUserId(),
            'role' => $log->getRole(),
            'context' => $log->getContext(),
            'payload' => $log->getPayload(),
            'timestamp' => $log->getTimestamp(),
        ];
        if ($log->getId()) {
            $this->collection->updateOne(['_id' => new ObjectId($log->getId())], ['$set' => $data]);
        } else {
            $this->collection->insertOne($data);
        }
    }
}
