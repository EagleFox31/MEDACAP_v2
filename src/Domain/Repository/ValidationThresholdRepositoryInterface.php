<?php

namespace Domain\Repository;

use Domain\Model\ValidationThreshold;

interface ValidationThresholdRepositoryInterface
{
    public function findById($id): ?ValidationThreshold;
    public function findAll(): array;
    public function save(ValidationThreshold $threshold): void;
    public function delete($id): void;
}
