<?php

namespace Infrastructure\Mongo;

use Domain\Repository\KpiQuestionRepositoryInterface;
use Domain\Model\KpiQuestion;
use MongoDB\BSON\ObjectId;

class KpiQuestionRepository implements KpiQuestionRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('kpiQuestions');
    }

    public function save(KpiQuestion $kpi): void
    {
        $data = [
            'questionId' => $kpi->getQuestionId(),
            'nbApparitionsTotal' => $kpi->getNbApparitionsTotal(),
            'nbReponsesCorrectes' => $kpi->getNbReponsesCorrectes(),
            'tempsMoyenReponseBonne' => $kpi->getTempsMoyenReponseBonne(),
            'nbReponsesFausses' => $kpi->getNbReponsesFausses(),
            'tempsMoyenReponseFausses' => $kpi->getTempsMoyenReponseFausses(),
            'cooccurrencesQuestions' => $kpi->getCooccurrencesQuestions(),
            'tauxReussite' => $kpi->getTauxReussite(),
        ];
        if ($kpi->getId()) {
            $this->collection->updateOne(['_id' => new ObjectId($kpi->getId())], ['$set' => $data]);
        } else {
            $this->collection->insertOne($data);
        }
    }

    public function findByQuestion($questionId): ?KpiQuestion
    {
        $doc = $this->collection->findOne(['questionId' => $questionId]);
        if (!$doc) {
            return null;
        }
        return new KpiQuestion(
            (string) $doc['_id'],
            $doc['questionId'],
            $doc['nbApparitionsTotal'],
            $doc['nbReponsesCorrectes'],
            $doc['tempsMoyenReponseBonne'],
            $doc['nbReponsesFausses'],
            $doc['tempsMoyenReponseFausses'],
            $doc['cooccurrencesQuestions'],
            $doc['tauxReussite']
        );
    }
}
