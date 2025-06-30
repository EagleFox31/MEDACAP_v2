<?php

namespace Infrastructure\Mongo;

use Domain\Repository\FormateurRepositoryInterface;
use Domain\Model\Formateur;
use MongoDB\BSON\ObjectId;

class FormateurRepository implements FormateurRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('formateurs');
    }

    public function findById($id): ?Formateur
    {
        $doc = $this->collection->findOne(['_id' => new ObjectId($id)]);
        return $doc ? $this->map($doc) : null;
    }

    public function findAll(): array
    {
        $cursor = $this->collection->find();
        $result = [];
        foreach ($cursor as $doc) {
            $result[] = $this->map($doc);
        }
        return $result;
    }

    public function save(Formateur $formateur): void
    {
        $data = [
            'firstName' => $formateur->getFirstName(),
            'lastName' => $formateur->getLastName(),
            'email' => $formateur->getEmail(),
        ];
        if ($formateur->getId()) {
            $this->collection->updateOne(['_id' => new ObjectId($formateur->getId())], ['$set' => $data]);
        } else {
            $this->collection->insertOne($data);
        }
    }

    public function delete($id): void
    {
        $this->collection->deleteOne(['_id' => new ObjectId($id)]);
    }

    private function map($doc): Formateur
    {
        return new Formateur((string)$doc['_id'], $doc['firstName'], $doc['lastName'], $doc['email']);
    }
}
