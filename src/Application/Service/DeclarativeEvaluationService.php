<?php

namespace Application\Service;

use Domain\Repository\CommercialResultRepositoryInterface;
use Application\DTO\ResultDTO;
use Domain\Model\CommercialResult;

class DeclarativeEvaluationService
{
    private $resultRepository;

    public function __construct(CommercialResultRepositoryInterface $resultRepository)
    {
        $this->resultRepository = $resultRepository;
    }

    public function submitEvaluation(ResultDTO $dto): void
    {
        $result = new CommercialResult(
            null,
            $dto->quizId,
            $dto->userId,
            $dto->evaluationType,
            $dto->submittedBy,
            $dto->evaluatedRole,
            $dto->sessionType,
            $dto->answers,
            $dto->score,
            $dto->timeSpent,
            new \DateTime()
        );
        $this->resultRepository->save($result);
    }
}
