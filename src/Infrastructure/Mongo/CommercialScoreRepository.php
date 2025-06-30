<?php

namespace Infrastructure\Mongo;

use Domain\Repository\CommercialScoreRepositoryInterface;
use Domain\Model\CommercialScore;
use MongoDB\BSON\ObjectId;

class CommercialScoreRepository implements CommercialScoreRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('commercialScores');
    }

    public function save(CommercialScore $score): void
    {
        $data = [
            'userId' => new ObjectId($score->getUserId()),
            'levels' => $score->getLevels(),
        ];
        if ($score->getId()) {
            $this->collection->updateOne(['_id' => new ObjectId($score->getId())], ['$set' => $data]);
        } else {
            $this->collection->insertOne($data);
        }
    }

    public function findByUser($userId): ?CommercialScore
    {
        $doc = $this->collection->findOne(['userId' => new ObjectId($userId)]);
        if (!$doc) {
            return null;
        }
        return new CommercialScore((string) $doc['_id'], isset($doc['userId']) ? (string) $doc['userId'] : null, $doc['levels']);
    }
}
