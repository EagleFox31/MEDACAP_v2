<?php

namespace Domain\Repository;

use Domain\Model\CommercialQuestion;

interface CommercialQuestionRepositoryInterface
{
    public function findById($id): ?CommercialQuestion;
    public function save(CommercialQuestion $question): void;
    public function findByLevelAndSpeciality(string $level, string $speciality): array;
}
