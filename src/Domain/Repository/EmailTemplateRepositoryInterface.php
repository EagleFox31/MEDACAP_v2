<?php

namespace Domain\Repository;

use Domain\Model\EmailTemplate;

interface EmailTemplateRepositoryInterface
{
    public function findByCode(string $code): ?EmailTemplate;
    public function save(EmailTemplate $template): void;
}
