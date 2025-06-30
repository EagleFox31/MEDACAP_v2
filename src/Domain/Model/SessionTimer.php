<?php

namespace Domain\Model;

class SessionTimer
{
    private $id;
    private $userId;
    private $allocationId;
    private $quizId;
    private $sessionType;
    private $status;
    private $startedAt;
    private $lastActivityAt;
    private $durationLimitSec;
    private $elapsedSeconds;
    private $questionTracking = [];
    private $events = [];
    private $resumable;
    private $networkQuality;
    private $ipAddress;
    private $userAgent;
    private $createdAt;
    private $updatedAt;

    public function __construct(
        $id,
        $userId,
        $allocationId,
        $quizId,
        string $sessionType,
        string $status,
        \DateTime $startedAt,
        \DateTime $lastActivityAt = null,
        int $durationLimitSec = null,
        int $elapsedSeconds = 0,
        array $questionTracking = [],
        array $events = [],
        bool $resumable = true,
        string $networkQuality = null,
        string $ipAddress = null,
        string $userAgent = null,
        \DateTime $createdAt = null,
        \DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->allocationId = $allocationId;
        $this->quizId = $quizId;
        $this->sessionType = $sessionType;
        $this->status = $status;
        $this->startedAt = $startedAt;
        $this->lastActivityAt = $lastActivityAt;
        $this->durationLimitSec = $durationLimitSec;
        $this->elapsedSeconds = $elapsedSeconds;
        $this->questionTracking = $questionTracking;
        $this->events = $events;
        $this->resumable = $resumable;
        $this->networkQuality = $networkQuality;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
        $this->createdAt = $createdAt ?? new \DateTime();
        $this->updatedAt = $updatedAt;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getAllocationId()
    {
        return $this->allocationId;
    }

    public function getQuizId()
    {
        return $this->quizId;
    }

    public function getSessionType(): string
    {
        return $this->sessionType;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getStartedAt(): \DateTime
    {
        return $this->startedAt;
    }

    public function getLastActivityAt(): ?\DateTime
    {
        return $this->lastActivityAt;
    }

    public function getDurationLimitSec(): ?int
    {
        return $this->durationLimitSec;
    }

    public function getElapsedSeconds(): int
    {
        return $this->elapsedSeconds;
    }

    public function getQuestionTracking(): array
    {
        return $this->questionTracking;
    }

    public function getEvents(): array
    {
        return $this->events;
    }

    public function isResumable(): bool
    {
        return $this->resumable;
    }

    public function getNetworkQuality()
    {
        return $this->networkQuality;
    }

    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    public function getUserAgent()
    {
        return $this->userAgent;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }
}
