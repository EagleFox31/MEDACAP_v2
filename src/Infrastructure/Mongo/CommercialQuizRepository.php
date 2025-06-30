<?php

namespace Infrastructure\Mongo;

use Domain\Repository\CommercialQuizRepositoryInterface;
use Domain\Model\CommercialQuiz;
use MongoDB\BSON\ObjectId;

class CommercialQuizRepository implements CommercialQuizRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('commercialQuizzes');
    }

    public function findById($id): ?CommercialQuiz
    {
        $data = $this->collection->findOne(['_id' => new ObjectId($id)]);
        return $data ? $this->map($data) : null;
    }

    public function save(CommercialQuiz $quiz): void
    {
        $data = [
            'label' => $quiz->getLabel(),
            'level' => $quiz->getLevel(),
            'brand' => $quiz->getBrand(),
            'speciality' => $quiz->getSpeciality(),
            'groupLevelMappingId' => $quiz->getGroupLevelMappingId(),
            'groupId' => $quiz->getGroupId(),
            'taskId' => $quiz->getTaskId(),
            'tags' => $quiz->getTags(),
            'questions' => $quiz->getQuestionIds(),
            'visibleForLevels' => $quiz->getVisibleForLevels(),
        ];

        if ($quiz->getId()) {
            $this->collection->updateOne(['_id' => new ObjectId($quiz->getId())], ['$set' => $data]);
        } else {
            $this->collection->insertOne($data);
        }
    }

    public function findActiveByLevelAndBrand(string $level, string $brand): array
    {
        $cursor = $this->collection->find(['level' => $level, 'brand' => $brand]);
        $result = [];
        foreach ($cursor as $doc) {
            $result[] = $this->map($doc);
        }
        return $result;
    }

    private function map($doc): CommercialQuiz
    {
        return new CommercialQuiz(
            (string) $doc['_id'],
            $doc['label'],
            $doc['level'],
            $doc['brand'],
            $doc['speciality'],
            $doc['groupLevelMappingId'] ?? null,
            $doc['groupId'] ?? null,
            $doc['taskId'] ?? null,
            $doc['tags'] ?? [],
            $doc['questions'] ?? [],
            $doc['visibleForLevels'] ?? []
        );
    }
}
