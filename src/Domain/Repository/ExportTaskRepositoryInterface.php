<?php

namespace Domain\Repository;

use Domain\Model\ExportTask;

interface ExportTaskRepositoryInterface
{
    public function findById($id): ?ExportTask;
    public function findByUser($userId): array;
    public function save(ExportTask $task): void;
    public function delete($id): void;
}
