<?php

namespace Domain\Repository;

use Domain\Model\ProfessionalTask;

interface ProfessionalTaskRepositoryInterface
{
    public function findById($id): ?ProfessionalTask;
    public function findAll(): array;
    public function save(ProfessionalTask $task): void;
    public function delete($id): void;
}
