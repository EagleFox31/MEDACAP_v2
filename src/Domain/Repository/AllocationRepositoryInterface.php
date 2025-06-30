<?php

namespace Domain\Repository;

use Domain\Model\Allocation;

interface AllocationRepositoryInterface
{
    public function save(Allocation $allocation): void;
    public function findActiveByUser($userId): array;
}
