<?php

namespace Infrastructure\Mongo;

use Domain\Repository\RuleEngineConfigRepositoryInterface;
use Domain\Model\RuleEngineConfig;
use MongoDB\BSON\ObjectId;

class RuleEngineConfigRepository implements RuleEngineConfigRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('ruleEngineConfigs');
    }

    public function findById($id): ?RuleEngineConfig
    {
        $doc = $this->collection->findOne(['_id' => new ObjectId($id)]);
        return $doc ? $this->map($doc) : null;
    }

    public function findActiveByContext(string $context): array
    {
        $cursor = $this->collection->find(['context' => $context, 'active' => true]);
        $result = [];
        foreach ($cursor as $doc) {
            $result[] = $this->map($doc);
        }
        return $result;
    }

    public function save(RuleEngineConfig $config): void
    {
        $data = [
            'context' => $config->getContext(),
            'description' => $config->getDescription(),
            'targetLevel' => $config->getTargetLevel(),
            'brandId' => $config->getBrandId(),
            'groupIds' => $config->getGroupIds(),
            'taskTags' => $config->getTaskTags(),
            'ruleType' => $config->getRuleType(),
            'active' => $config->isActive(),
            'priority' => $config->getPriority(),
            'trigger' => $config->getTrigger(),
            'conditions' => $config->getConditions(),
            'actions' => $config->getActions(),
            'validFrom' => $config->getValidFrom(),
            'validTo' => $config->getValidTo(),
            'createdAt' => $config->getCreatedAt(),
            'createdBy' => $config->getCreatedBy(),
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

    private function map($doc): RuleEngineConfig
    {
        $createdAt = $doc['createdAt'] instanceof \MongoDB\BSON\UTCDateTime ? $doc['createdAt']->toDateTime() : new \DateTime();
        $validFrom = isset($doc['validFrom']) && $doc['validFrom'] instanceof \MongoDB\BSON\UTCDateTime ? $doc['validFrom']->toDateTime() : null;
        $validTo = isset($doc['validTo']) && $doc['validTo'] instanceof \MongoDB\BSON\UTCDateTime ? $doc['validTo']->toDateTime() : null;
        return new RuleEngineConfig(
            (string) $doc['_id'],
            $doc['context'],
            $doc['description'] ?? '',
            $doc['targetLevel'] ?? null,
            $doc['brandId'] ?? null,
            $doc['groupIds'] ?? [],
            $doc['taskTags'] ?? [],
            $doc['ruleType'],
            $doc['active'] ?? true,
            $doc['priority'] ?? 0,
            $doc['trigger'] ?? 'manual',
            $doc['conditions'] ?? [],
            $doc['actions'] ?? [],
            $validFrom,
            $validTo,
            $createdAt,
            $doc['createdBy'] ?? null
        );
    }
}
