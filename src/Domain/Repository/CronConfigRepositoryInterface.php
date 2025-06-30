<?php

namespace Domain\Repository;

use Domain\Model\CronConfig;

interface CronConfigRepositoryInterface
{
    public function findById($id): ?CronConfig;
    public function findAll(): array;
    public function save(CronConfig $config): void;
    public function delete($id): void;
}
