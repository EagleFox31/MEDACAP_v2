<?php

namespace Domain\Repository;

use Domain\Model\Formateur;

interface FormateurRepositoryInterface
{
    public function findById($id): ?Formateur;
    public function findAll(): array;
    public function save(Formateur $formateur): void;
    public function delete($id): void;
}
