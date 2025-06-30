<?php

namespace Infrastructure\Mongo;

use Domain\Repository\SessionTimerRepositoryInterface;
use Domain\Model\SessionTimer;
use MongoDB\BSON\ObjectId;

class SessionTimerRepository implements SessionTimerRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('sessionTimers');
    }

    public function findById($id): ?SessionTimer
    {
        $doc = $this->collection->findOne(['_id' => new ObjectId($id)]);
        return $doc ? $this->map($doc) : null;
    }

    public function save(SessionTimer $timer): void
    {
        $data = [
            'userId' => $timer->getUserId(),
            'allocationId' => $timer->getAllocationId(),
            'quizId' => $timer->getQuizId(),
            'sessionType' => $timer->getSessionType(),
            'status' => $timer->getStatus(),
            'startedAt' => $timer->getStartedAt(),
            'lastActivityAt' => $timer->getLastActivityAt(),
            'durationLimitSec' => $timer->getDurationLimitSec(),
            'elapsedSeconds' => $timer->getElapsedSeconds(),
            'questionTracking' => $timer->getQuestionTracking(),
            'events' => $timer->getEvents(),
            'resumable' => $timer->isResumable(),
            'networkQuality' => $timer->getNetworkQuality(),
            'ipAddress' => $timer->getIpAddress(),
            'userAgent' => $timer->getUserAgent(),
            'createdAt' => $timer->getCreatedAt(),
            'updatedAt' => $timer->getUpdatedAt(),
        ];
        if ($timer->getId()) {
            $this->collection->updateOne(['_id' => new ObjectId($timer->getId())], ['$set' => $data]);
        } else {
            $this->collection->insertOne($data);
        }
    }

    public function findActiveByUser($userId): array
    {
        $cursor = $this->collection->find(['userId' => $userId, 'status' => 'enCours']);
        $result = [];
        foreach ($cursor as $doc) {
            $result[] = $this->map($doc);
        }
        return $result;
    }

    private function map($doc): SessionTimer
    {
        $createdAt = $doc['createdAt'] instanceof \MongoDB\BSON\UTCDateTime ? $doc['createdAt']->toDateTime() : new \DateTime();
        $updatedAt = isset($doc['updatedAt']) && $doc['updatedAt'] instanceof \MongoDB\BSON\UTCDateTime ? $doc['updatedAt']->toDateTime() : null;
        $lastActivityAt = isset($doc['lastActivityAt']) && $doc['lastActivityAt'] instanceof \MongoDB\BSON\UTCDateTime ? $doc['lastActivityAt']->toDateTime() : null;
        $startedAt = $doc['startedAt'] instanceof \MongoDB\BSON\UTCDateTime ? $doc['startedAt']->toDateTime() : new \DateTime();
        return new SessionTimer(
            (string) $doc['_id'],
            $doc['userId'],
            $doc['allocationId'] ?? null,
            $doc['quizId'] ?? null,
            $doc['sessionType'],
            $doc['status'],
            $startedAt,
            $lastActivityAt,
            $doc['durationLimitSec'] ?? null,
            $doc['elapsedSeconds'] ?? 0,
            $doc['questionTracking'] ?? [],
            $doc['events'] ?? [],
            $doc['resumable'] ?? true,
            $doc['networkQuality'] ?? null,
            $doc['ipAddress'] ?? null,
            $doc['userAgent'] ?? null,
            $createdAt,
            $updatedAt
        );
    }
}
