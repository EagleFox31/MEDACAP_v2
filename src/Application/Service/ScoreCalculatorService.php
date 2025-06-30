<?php

namespace Application\Service;

use Domain\Repository\CommercialResultRepositoryInterface;
use Domain\Repository\CommercialScoreRepositoryInterface;
use Domain\Model\CommercialScore;

class ScoreCalculatorService
{
    private $resultRepository;
    private $scoreRepository;

    public function __construct(
        CommercialResultRepositoryInterface $resultRepository,
        CommercialScoreRepositoryInterface $scoreRepository
    ) {
        $this->resultRepository = $resultRepository;
        $this->scoreRepository = $scoreRepository;
    }

    public function updateScore($userId, $quizId, float $score): void
    {
        $existing = $this->scoreRepository->findByUser($userId);
        $levels = $existing ? $existing->getLevels() : [];
        $levels[$quizId] = $score;
        $scoreObj = new CommercialScore($existing ? $existing->getId() : null, $userId, $levels);
        $this->scoreRepository->save($scoreObj);
    }
}
