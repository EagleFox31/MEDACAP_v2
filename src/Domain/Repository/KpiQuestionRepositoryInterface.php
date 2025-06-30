<?php

namespace Domain\Repository;

use Domain\Model\KpiQuestion;

interface KpiQuestionRepositoryInterface
{
    public function save(KpiQuestion $kpi): void;
    public function findByQuestion($questionId): ?KpiQuestion;
}
