<?php

namespace Domain\Repository;

use Domain\Model\DashboardConfig;

interface DashboardConfigRepositoryInterface
{
    public function findById($id): ?DashboardConfig;
    public function findByUser($userId): array;
    public function save(DashboardConfig $config): void;
    public function delete($id): void;
}
