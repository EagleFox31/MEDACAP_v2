<?php

namespace Domain\Repository;

use Domain\Model\CommercialQuiz;

interface CommercialQuizRepositoryInterface
{
    public function findById($id): ?CommercialQuiz;
    public function save(CommercialQuiz $quiz): void;
    public function findActiveByLevelAndBrand(string $level, string $brand): array;
}
