<?php

namespace Domain\Repository;

use Domain\Model\Manager;

interface ManagerRepositoryInterface
{
    public function findById($id): ?Manager;
    public function findAll(): array;
    public function save(Manager $manager): void;
    public function delete($id): void;
}
