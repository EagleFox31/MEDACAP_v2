<?php

namespace Infrastructure\Mongo;

use Domain\Repository\DashboardConfigRepositoryInterface;
use Domain\Model\DashboardConfig;
use MongoDB\BSON\ObjectId;

class DashboardConfigRepository implements DashboardConfigRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('dashboardConfigs');
    }

    public function findById($id): ?DashboardConfig
    {
        $doc = $this->collection->findOne(['_id' => new ObjectId($id)]);
        return $doc ? $this->map($doc) : null;
    }

    public function findByUser($userId): array
    {
        $cursor = $this->collection->find(['userId' => new ObjectId($userId)]);
        $result = [];
        foreach ($cursor as $doc) {
            $result[] = $this->map($doc);
        }
        return $result;
    }

    public function save(DashboardConfig $config): void
    {
        $data = [
            'userId' => $config->getUserId(),
            'role' => $config->getRole(),
            'viewId' => $config->getViewId(),
            'title' => $config->getTitle(),
            'description' => $config->getDescription(),
            'filters' => $config->getFilters(),
            'columns' => $config->getColumns(),
            'sort' => $config->getSort(),
            'refreshIntervalSec' => $config->getRefreshIntervalSec(),
            'chartConfig' => $config->getChartConfig(),
            'exportPreferences' => $config->getExportPreferences(),
            'pinned' => $config->isPinned(),
            'isDefault' => $config->isDefault(),
            'sharedWithRoles' => $config->getSharedWithRoles(),
            'createdAt' => $config->getCreatedAt(),
            'updatedAt' => $config->getUpdatedAt(),
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

    private function map($doc): DashboardConfig
    {
        $createdAt = $doc['createdAt'] instanceof \MongoDB\BSON\UTCDateTime ? $doc['createdAt']->toDateTime() : new \DateTime();
        $updatedAt = isset($doc['updatedAt']) && $doc['updatedAt'] instanceof \MongoDB\BSON\UTCDateTime ? $doc['updatedAt']->toDateTime() : null;
        return new DashboardConfig(
            (string) $doc['_id'],
            isset($doc['userId']) ? (string) $doc['userId'] : null,
            $doc['role'],
            $doc['viewId'],
            $doc['title'],
            $doc['description'] ?? null,
            $doc['filters'] ?? [],
            $doc['columns'] ?? [],
            $doc['sort'] ?? [],
            $doc['refreshIntervalSec'] ?? null,
            $doc['chartConfig'] ?? [],
            $doc['exportPreferences'] ?? [],
            $doc['pinned'] ?? false,
            $doc['isDefault'] ?? false,
            $doc['sharedWithRoles'] ?? [],
            $createdAt,
            $updatedAt
        );
    }
}
