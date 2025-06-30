<?php

namespace Domain\Model;

class LogAction
{
    private $id;
    private $type;
    private $userId;
    private $role;
    private $context = [];
    private $payload = [];
    private $timestamp;

    public function __construct(
        $id,
        string $type,
        $userId,
        string $role,
        array $context = [],
        array $payload = [],
        \DateTime $timestamp
    ) {
        $this->id = $id;
        $this->type = $type;
        $this->userId = $userId;
        $this->role = $role;
        $this->context = $context;
        $this->payload = $payload;
        $this->timestamp = $timestamp;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }
}
