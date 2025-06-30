<?php

namespace Domain\Model;

class Allocation
{
    private $id;
    private $userId;
    private $quizId;
    private $level;
    private $brand;
    private $active;
    private $sessionStatus;

    public function __construct(
        $id,
        $userId,
        $quizId,
        string $level,
        string $brand,
        bool $active,
        string $sessionStatus
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->quizId = $quizId;
        $this->level = $level;
        $this->brand = $brand;
        $this->active = $active;
        $this->sessionStatus = $sessionStatus;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getQuizId()
    {
        return $this->quizId;
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getSessionStatus(): string
    {
        return $this->sessionStatus;
    }
}
