<?php

namespace Domain\Repository;

use Domain\Model\GroupLevelMapping;

interface GroupLevelMappingRepositoryInterface
{
    public function findById($id): ?GroupLevelMapping;
    public function findAll(): array;
    public function save(GroupLevelMapping $mapping): void;
    public function delete($id): void;
}
