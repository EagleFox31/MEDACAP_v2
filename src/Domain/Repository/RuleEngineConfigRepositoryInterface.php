<?php

namespace Domain\Repository;

use Domain\Model\RuleEngineConfig;

interface RuleEngineConfigRepositoryInterface
{
    public function findById($id): ?RuleEngineConfig;
    public function findActiveByContext(string $context): array;
    public function save(RuleEngineConfig $config): void;
    public function delete($id): void;
}
