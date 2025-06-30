<?php

namespace Domain\Repository;

use Domain\Model\EmailLog;

interface EmailLogRepositoryInterface
{
    public function save(EmailLog $log): void;
}
