<?php

namespace Domain\Repository;

use Domain\Model\SessionTimer;

interface SessionTimerRepositoryInterface
{
    public function findById($id): ?SessionTimer;
    public function save(SessionTimer $timer): void;
    public function findActiveByUser($userId): array;
}
