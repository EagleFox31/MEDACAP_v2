<?php

namespace Infrastructure\Mongo;

use Domain\Repository\CommercialResultRepositoryInterface;
use Domain\Model\CommercialResult;
use MongoDB\BSON\ObjectId;

class CommercialResultRepository implements CommercialResultRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('commercialResults');
    }

    public function save(CommercialResult $result): void
    {
        $data = [
            'quizId' => $result->getQuizId(),
            'userId' => $result->getUserId(),
            'evaluationType' => $result->getEvaluationType(),
            'submittedBy' => $result->getSubmittedBy(),
            'evaluatedRole' => $result->getEvaluatedRole(),
            'sessionType' => $result->getSessionType(),
            'answers' => $result->getAnswers(),
            'score' => $result->getScore(),
            'timeSpent' => $result->getTimeSpent(),
            'submittedAt' => $result->getSubmittedAt(),
        ];
        if ($result->getId()) {
            $this->collection->updateOne(['_id' => new ObjectId($result->getId())], ['$set' => $data]);
        } else {
            $this->collection->insertOne($data);
        }
    }

    public function findByUserAndQuiz($userId, $quizId): ?CommercialResult
    {
        $doc = $this->collection->findOne(['userId' => $userId, 'quizId' => $quizId]);
        return $doc ? $this->map($doc) : null;
    }

    private function map($doc): CommercialResult
    {
        return new CommercialResult(
            (string) $doc['_id'],
            $doc['quizId'],
            $doc['userId'],
            $doc['evaluationType'],
            $doc['submittedBy'],
            $doc['evaluatedRole'],
            $doc['sessionType'],
            $doc['answers'] ?? [],
            $doc['score'],
            $doc['timeSpent'],
            $doc['submittedAt'] instanceof \MongoDB\BSON\UTCDateTime ? $doc['submittedAt']->toDateTime() : new \DateTime()
        );
    }
}
