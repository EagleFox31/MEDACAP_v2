<?php

namespace Domain\Repository;

use Domain\Model\CommercialResult;

interface CommercialResultRepositoryInterface
{
    public function save(CommercialResult $result): void;
    public function findByUserAndQuiz($userId, $quizId): ?CommercialResult;
}
