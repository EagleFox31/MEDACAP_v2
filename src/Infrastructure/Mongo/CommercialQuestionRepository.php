<?php

namespace Infrastructure\Mongo;

use Domain\Repository\CommercialQuestionRepositoryInterface;
use Domain\Model\CommercialQuestion;
use MongoDB\BSON\ObjectId;

class CommercialQuestionRepository implements CommercialQuestionRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('commercialQuestions');
    }

    public function findById($id): ?CommercialQuestion
    {
        $doc = $this->collection->findOne(['_id' => new ObjectId($id)]);
        return $doc ? $this->map($doc) : null;
    }

    public function save(CommercialQuestion $question): void
    {
        $data = [
            'label' => $question->getLabel(),
            'tags' => $question->getTags(),
            'level' => $question->getLevel(),
            'speciality' => $question->getSpeciality(),
            'department' => $question->getDepartment(),
            'groupLevelMappingId' => $question->getGroupLevelMappingId(),
            'groupId' => $question->getGroupId(),
            'taskId' => $question->getTaskId(),
            'type' => $question->getType(),
            'statistics' => $question->getStatistics(),
        ];
        if ($question->getId()) {
            $this->collection->updateOne(['_id' => new ObjectId($question->getId())], ['$set' => $data]);
        } else {
            $this->collection->insertOne($data);
        }
    }

    public function findByLevelAndSpeciality(string $level, string $speciality): array
    {
        $cursor = $this->collection->find(['level' => $level, 'speciality' => $speciality]);
        $result = [];
        foreach ($cursor as $doc) {
            $result[] = $this->map($doc);
        }
        return $result;
    }

    private function map($doc): CommercialQuestion
    {
        return new CommercialQuestion(
            (string) $doc['_id'],
            $doc['label'],
            $doc['tags'] ?? [],
            $doc['level'],
            $doc['speciality'],
            $doc['department'],
            $doc['groupLevelMappingId'] ?? null,
            $doc['groupId'] ?? null,
            $doc['taskId'] ?? null,
            $doc['type'],
            $doc['statistics'] ?? []
        );
    }
}
