<?php

namespace Domain\Model;

class ExportTask
{
    private $id;
    private $requestedBy;
    private $role;
    private $exportType;
    private $description;
    private $filters = [];
    private $columns = [];
    private $format;
    private $status;
    private $startedAt;
    private $completedAt;
    private $durationMs;
    private $downloadUrl;
    private $fileSizeBytes;
    private $expiresAt;
    private $notified;
    private $error;
    private $createdAt;
    private $updatedAt;

    public function __construct(
        $id,
        $requestedBy,
        string $role,
        string $exportType,
        string $description,
        array $filters = [],
        array $columns = [],
        string $format = 'excel',
        string $status = 'pending',
        \DateTime $startedAt = null,
        \DateTime $completedAt = null,
        int $durationMs = null,
        string $downloadUrl = null,
        int $fileSizeBytes = null,
        \DateTime $expiresAt = null,
        bool $notified = false,
        $error = null,
        \DateTime $createdAt = null,
        \DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->requestedBy = $requestedBy;
        $this->role = $role;
        $this->exportType = $exportType;
        $this->description = $description;
        $this->filters = $filters;
        $this->columns = $columns;
        $this->format = $format;
        $this->status = $status;
        $this->startedAt = $startedAt;
        $this->completedAt = $completedAt;
        $this->durationMs = $durationMs;
        $this->downloadUrl = $downloadUrl;
        $this->fileSizeBytes = $fileSizeBytes;
        $this->expiresAt = $expiresAt;
        $this->notified = $notified;
        $this->error = $error;
        $this->createdAt = $createdAt ?? new \DateTime();
        $this->updatedAt = $updatedAt;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRequestedBy()
    {
        return $this->requestedBy;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getExportType(): string
    {
        return $this->exportType;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getStartedAt(): ?\DateTime
    {
        return $this->startedAt;
    }

    public function getCompletedAt(): ?\DateTime
    {
        return $this->completedAt;
    }

    public function getDurationMs(): ?int
    {
        return $this->durationMs;
    }

    public function getDownloadUrl(): ?string
    {
        return $this->downloadUrl;
    }

    public function getFileSizeBytes(): ?int
    {
        return $this->fileSizeBytes;
    }

    public function getExpiresAt(): ?\DateTime
    {
        return $this->expiresAt;
    }

    public function isNotified(): bool
    {
        return $this->notified;
    }

    public function getError()
    {
        return $this->error;
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
