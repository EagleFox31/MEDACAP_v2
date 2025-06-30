<?php

namespace Domain\Model;

class CommercialResult
{
    private $id;
    private $quizId;
    private $userId;
    private $evaluationType; // factuel|declaratif
    private $submittedBy; // user|manager|system
    private $evaluatedRole; // commercial
    private $sessionType; // auto|cross|final
    private $answers = [];
    private $score;
    private $timeSpent;
    private $submittedAt;

    public function __construct(
        $id,
        $quizId,
        $userId,
        string $evaluationType,
        string $submittedBy,
        string $evaluatedRole,
        string $sessionType,
        array $answers,
        float $score,
        int $timeSpent,
        \DateTime $submittedAt
    ) {
        $this->id = $id;
        $this->quizId = $quizId;
        $this->userId = $userId;
        $this->evaluationType = $evaluationType;
        $this->submittedBy = $submittedBy;
        $this->evaluatedRole = $evaluatedRole;
        $this->sessionType = $sessionType;
        $this->answers = $answers;
        $this->score = $score;
        $this->timeSpent = $timeSpent;
        $this->submittedAt = $submittedAt;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getQuizId()
    {
        return $this->quizId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getEvaluationType(): string
    {
        return $this->evaluationType;
    }

    public function getSubmittedBy(): string
    {
        return $this->submittedBy;
    }

    public function getEvaluatedRole(): string
    {
        return $this->evaluatedRole;
    }

    public function getSessionType(): string
    {
        return $this->sessionType;
    }

    public function getAnswers(): array
    {
        return $this->answers;
    }

    public function getScore(): float
    {
        return $this->score;
    }

    public function getTimeSpent(): int
    {
        return $this->timeSpent;
    }

    public function getSubmittedAt(): \DateTime
    {
        return $this->submittedAt;
    }
}
