<?php

namespace Domain\Repository;

use Domain\Model\KpiScore;

interface KpiScoreRepositoryInterface
{
    public function save(KpiScore $kpi): void;
    public function findByContext(array $context): ?KpiScore;
}
