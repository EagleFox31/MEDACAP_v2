<?php
require __DIR__ . '/../../../vendor/autoload.php';

use Infrastructure\Mongo\MongoConnection;
use Infrastructure\Mongo\CommercialQuizRepository;
use Infrastructure\Mongo\AllocationRepository;
use Application\Service\QuizAssignmentService;

$mongo = new MongoConnection('mongodb://localhost:27017', 'academy_commercial');
$quizRepo = new CommercialQuizRepository($mongo);
$allocationRepo = new AllocationRepository($mongo);
$service = new QuizAssignmentService($quizRepo, $allocationRepo);

// Example usage: php assign_quizzes.php userId level brand
$userId = $argv[1] ?? null;
$level = $argv[2] ?? 'Junior';
$brand = $argv[3] ?? 'Default';

if (!$userId) {
    echo "Usage: php assign_quizzes.php userId level brand\n";
    exit(1);
}

$service->assignQuizzesToUser($userId, $level, $brand);

echo "Quizzes assigned." . PHP_EOL;
