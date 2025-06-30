<?php

namespace Domain\Repository;

use Domain\Model\Brand;

interface BrandRepositoryInterface
{
    public function findById($id): ?Brand;
    public function findAll(): array;
    public function save(Brand $brand): void;
    public function delete($id): void;
}
