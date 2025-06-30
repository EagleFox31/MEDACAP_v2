<?php

namespace Domain\Repository;

use Domain\Model\Level;

interface LevelRepositoryInterface
{
    public function findById($id): ?Level;
    public function findAll(): array;
    public function save(Level $level): void;
    public function delete($id): void;
}
