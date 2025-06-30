<?php

namespace Infrastructure\Mongo;

use Domain\Repository\EmailLogRepositoryInterface;
use Domain\Model\EmailLog;
use MongoDB\BSON\ObjectId;

class EmailLogRepository implements EmailLogRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('emailLogs');
    }

    public function save(EmailLog $log): void
    {
        $data = [
            'templateCode' => $log->getTemplateCode(),
            'userId' => $log->getUserId(),
            'role' => $log->getRole(),
            'status' => $log->getStatus(),
            'payload' => $log->getPayload(),
            'error' => $log->getError(),
            'timestamp' => $log->getTimestamp(),
        ];
        if ($log->getId()) {
            $this->collection->updateOne(['_id' => new ObjectId($log->getId())], ['$set' => $data]);
        } else {
            $this->collection->insertOne($data);
        }
    }
}
