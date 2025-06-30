<?php

namespace Infrastructure\Mongo;

use Domain\Repository\CommercialUserRepositoryInterface;
use Domain\Model\CommercialUser;
use MongoDB\BSON\ObjectId;

class CommercialUserRepository implements CommercialUserRepositoryInterface
{
    private $collection;

    public function __construct(MongoConnection $connection)
    {
        $this->collection = $connection->getDatabase()->selectCollection('commercialUsers');
    }

    public function findById($id): ?CommercialUser
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

    public function save(CommercialUser $user): void
    {
        $data = [
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'filiale' => $user->getFiliale(),
            'agence' => $user->getAgence(),
            'level' => $user->getLevel(),
            'department' => $user->getDepartment(),
            'brandsByLevel' => $user->getBrandsByLevel(),
            'managerId' => $user->getManagerId() ? new ObjectId($user->getManagerId()) : null,
            'passwordHash' => $user->getPasswordHash(),
            'visiblePassword' => $user->getVisiblePassword(),
        ];

        if ($user->getId()) {
            $this->collection->updateOne(['_id' => new ObjectId($user->getId())], ['$set' => $data]);
        } else {
            $this->collection->insertOne($data);
        }
    }

    public function delete($id): void
    {
        $this->collection->deleteOne(['_id' => new ObjectId($id)]);
    }

    private function map($doc): CommercialUser
    {
        return new CommercialUser(
            (string) $doc['_id'],
            $doc['firstName'],
            $doc['lastName'],
            $doc['filiale'],
            $doc['agence'],
            $doc['level'],
            $doc['department'],
            $doc['brandsByLevel'] ?? [],
            isset($doc['managerId']) ? (string)$doc['managerId'] : null,
            $doc['passwordHash'] ?? null,
            $doc['visiblePassword'] ?? null
        );
    }
}
