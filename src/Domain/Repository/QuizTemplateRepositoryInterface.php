<?php

namespace Domain\Repository;

use Domain\Model\QuizTemplate;

interface QuizTemplateRepositoryInterface
{
    public function findById($id): ?QuizTemplate;
    public function save(QuizTemplate $template): void;
}
