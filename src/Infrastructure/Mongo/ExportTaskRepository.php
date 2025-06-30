<?php

namespace Infrastructure\Mongo;

use Domain\Repository\ExportTaskRepositoryInterface;
use Domain\Model\ExportTask;
use MongoDB\BSON\ObjectId;

class ExportTaskRepository implements ExportTaskRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('exportTasks');
    }

    public function findById($id): ?ExportTask
    {
        $doc = $this->collection->findOne(['_id' => new ObjectId($id)]);
        return $doc ? $this->map($doc) : null;
    }

    public function findByUser($userId): array
    {
        $cursor = $this->collection->find(['requestedBy' => new ObjectId($userId)]);
        $result = [];
        foreach ($cursor as $doc) {
            $result[] = $this->map($doc);
        }
        return $result;
    }

    public function save(ExportTask $task): void
    {
        $data = [
            'requestedBy' => new ObjectId($task->getRequestedBy()),
            'role' => $task->getRole(),
            'exportType' => $task->getExportType(),
            'description' => $task->getDescription(),
            'filters' => $task->getFilters(),
            'columns' => $task->getColumns(),
            'format' => $task->getFormat(),
            'status' => $task->getStatus(),
            'startedAt' => $task->getStartedAt(),
            'completedAt' => $task->getCompletedAt(),
            'durationMs' => $task->getDurationMs(),
            'downloadUrl' => $task->getDownloadUrl(),
            'fileSizeBytes' => $task->getFileSizeBytes(),
            'expiresAt' => $task->getExpiresAt(),
            'notified' => $task->isNotified(),
            'error' => $task->getError(),
            'createdAt' => $task->getCreatedAt(),
            'updatedAt' => $task->getUpdatedAt(),
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

    private function map($doc): ExportTask
    {
        $createdAt = $doc['createdAt'] instanceof \MongoDB\BSON\UTCDateTime ? $doc['createdAt']->toDateTime() : new \DateTime();
        $updatedAt = isset($doc['updatedAt']) && $doc['updatedAt'] instanceof \MongoDB\BSON\UTCDateTime ? $doc['updatedAt']->toDateTime() : null;
        $startedAt = isset($doc['startedAt']) && $doc['startedAt'] instanceof \MongoDB\BSON\UTCDateTime ? $doc['startedAt']->toDateTime() : null;
        $completedAt = isset($doc['completedAt']) && $doc['completedAt'] instanceof \MongoDB\BSON\UTCDateTime ? $doc['completedAt']->toDateTime() : null;
        $expiresAt = isset($doc['expiresAt']) && $doc['expiresAt'] instanceof \MongoDB\BSON\UTCDateTime ? $doc['expiresAt']->toDateTime() : null;
        return new ExportTask(
            (string) $doc['_id'],
            isset($doc['requestedBy']) ? (string) $doc['requestedBy'] : null,
            $doc['role'],
            $doc['exportType'],
            $doc['description'],
            $doc['filters'] ?? [],
            $doc['columns'] ?? [],
            $doc['format'] ?? 'excel',
            $doc['status'] ?? 'pending',
            $startedAt,
            $completedAt,
            $doc['durationMs'] ?? null,
            $doc['downloadUrl'] ?? null,
            $doc['fileSizeBytes'] ?? null,
            $expiresAt,
            $doc['notified'] ?? false,
            $doc['error'] ?? null,
            $createdAt,
            $updatedAt
        );
    }
}
