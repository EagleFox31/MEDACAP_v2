<?php

namespace Domain\Model;

class EmailLog
{
    private $id;
    private $templateCode;
    private $userId;
    private $role;
    private $status;
    private $payload;
    private $error;
    private $timestamp;

    public function __construct(
        $id,
        string $templateCode,
        $userId,
        string $role,
        string $status,
        array $payload,
        ?string $error,
        \DateTime $timestamp
    ) {
        $this->id = $id;
        $this->templateCode = $templateCode;
        $this->userId = $userId;
        $this->role = $role;
        $this->status = $status;
        $this->payload = $payload;
        $this->error = $error;
        $this->timestamp = $timestamp;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTemplateCode(): string
    {
        return $this->templateCode;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function getTimestamp(): \DateTime
    {
        return $this->timestamp;
    }
}
