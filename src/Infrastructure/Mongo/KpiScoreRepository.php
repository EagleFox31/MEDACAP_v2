<?php

namespace Infrastructure\Mongo;

use Domain\Repository\KpiScoreRepositoryInterface;
use Domain\Model\KpiScore;
use MongoDB\BSON\ObjectId;

class KpiScoreRepository implements KpiScoreRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('kpiScores');
    }

    public function save(KpiScore $kpi): void
    {
        $data = [
            'context' => $kpi->getContext(),
            'averages' => $kpi->getAverages(),
            'masteryRate' => $kpi->getMasteryRate(),
        ];
        if ($kpi->getId()) {
            $this->collection->updateOne(['_id' => new ObjectId($kpi->getId())], ['$set' => $data]);
        } else {
            $this->collection->insertOne($data);
        }
    }

    public function findByContext(array $context): ?KpiScore
    {
        $doc = $this->collection->findOne(['context' => $context]);
        if (!$doc) {
            return null;
        }
        return new KpiScore((string) $doc['_id'], $doc['context'], $doc['averages'], $doc['masteryRate']);
    }
}
