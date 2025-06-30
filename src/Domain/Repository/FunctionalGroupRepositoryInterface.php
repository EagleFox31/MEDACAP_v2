<?php

namespace Domain\Repository;

use Domain\Model\FunctionalGroup;

interface FunctionalGroupRepositoryInterface
{
    public function findById($id): ?FunctionalGroup;
    public function findAll(): array;
    public function save(FunctionalGroup $group): void;
    public function delete($id): void;
}
