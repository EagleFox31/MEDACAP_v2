<?php

namespace Domain\Repository;

use Domain\Model\CommercialUser;

interface CommercialUserRepositoryInterface
{
    public function findById($id): ?CommercialUser;
    public function findAll(): array;
    public function save(CommercialUser $user): void;
    public function delete($id): void;
}
