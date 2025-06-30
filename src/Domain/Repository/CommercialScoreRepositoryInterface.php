<?php

namespace Domain\Repository;

use Domain\Model\CommercialScore;

interface CommercialScoreRepositoryInterface
{
    public function save(CommercialScore $score): void;
    public function findByUser($userId): ?CommercialScore;
}
