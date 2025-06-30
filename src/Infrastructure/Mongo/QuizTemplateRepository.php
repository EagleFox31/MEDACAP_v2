<?php

namespace Infrastructure\Mongo;

use Domain\Repository\QuizTemplateRepositoryInterface;
use Domain\Model\QuizTemplate;
use MongoDB\BSON\ObjectId;

class QuizTemplateRepository implements QuizTemplateRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('quizTemplates');
    }

    public function findById($id): ?QuizTemplate
    {
        $doc = $this->collection->findOne(['_id' => new ObjectId($id)]);
        return $doc ? $this->map($doc) : null;
    }

    public function save(QuizTemplate $template): void
    {
        $data = [
            'label' => $template->getLabel(),
            'description' => $template->getDescription(),
            'niveauCible' => $template->getNiveauCible(),
            'departement' => $template->getDepartement(),
            'groupId' => $template->getGroupId(),
            'brandId' => $template->getBrandId(),
            'visibleForLevels' => $template->getVisibleForLevels(),
            'taskIds' => $template->getTaskIds(),
            'tagDistribution' => $template->getTagDistribution(),
            'questionCount' => $template->getQuestionCount(),
            'selectionMode' => $template->getSelectionMode(),
            'randomizeOrder' => $template->shouldRandomizeOrder(),
            'allowMarking' => $template->isAllowMarking(),
            'allowReview' => $template->isAllowReview(),
            'templateType' => $template->getTemplateType(),
            'active' => $template->isActive(),
            'createdAt' => $template->getCreatedAt(),
            'createdBy' => $template->getCreatedBy(),
            'usageStats' => $template->getUsageStats(),
        ];
        if ($template->getId()) {
            $this->collection->updateOne(['_id' => new ObjectId($template->getId())], ['$set' => $data]);
        } else {
            $this->collection->insertOne($data);
        }
    }

    private function map($doc): QuizTemplate
    {
        $createdAt = $doc['createdAt'] instanceof \MongoDB\BSON\UTCDateTime ? $doc['createdAt']->toDateTime() : new \DateTime();
        return new QuizTemplate(
            (string) $doc['_id'],
            $doc['label'],
            $doc['description'] ?? '',
            $doc['niveauCible'],
            $doc['departement'],
            $doc['groupId'] ?? null,
            $doc['brandId'] ?? null,
            $doc['visibleForLevels'] ?? [],
            $doc['taskIds'] ?? [],
            $doc['tagDistribution'] ?? [],
            $doc['questionCount'] ?? 0,
            $doc['selectionMode'] ?? 'pondere',
            $doc['randomizeOrder'] ?? false,
            $doc['allowMarking'] ?? false,
            $doc['allowReview'] ?? false,
            $doc['templateType'] ?? 'factuel',
            $doc['active'] ?? true,
            $createdAt,
            $doc['createdBy'] ?? null,
            $doc['usageStats'] ?? []
        );
    }
}
