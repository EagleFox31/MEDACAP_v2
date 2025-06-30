<?php

namespace Domain\Repository;

use Domain\Model\CacheStore;

interface CacheStoreRepositoryInterface
{
    public function findById($id): ?CacheStore;
    public function findAll(): array;
    public function save(CacheStore $entry): void;
    public function delete($id): void;
}
