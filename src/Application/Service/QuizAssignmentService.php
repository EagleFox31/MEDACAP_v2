<?php

namespace Application\Service;

use Domain\Repository\CommercialQuizRepositoryInterface;
use Domain\Repository\AllocationRepositoryInterface;

class QuizAssignmentService
{
    private $quizRepository;
    private $allocationRepository;

    public function __construct(
        CommercialQuizRepositoryInterface $quizRepository,
        AllocationRepositoryInterface $allocationRepository
    ) {
        $this->quizRepository = $quizRepository;
        $this->allocationRepository = $allocationRepository;
    }

    public function assignQuizzesToUser($userId, string $level, string $brand): void
    {
        $quizzes = $this->quizRepository->findActiveByLevelAndBrand($level, $brand);
        foreach ($quizzes as $quiz) {
            $allocation = new \Domain\Model\Allocation(null, $userId, $quiz->getId(), $level, $brand, true, 'enCours');
            $this->allocationRepository->save($allocation);
        }
    }
}
