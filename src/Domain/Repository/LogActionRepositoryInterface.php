<?php

namespace Domain\Repository;

use Domain\Model\LogAction;

interface LogActionRepositoryInterface
{
    public function save(LogAction $log): void;
}
