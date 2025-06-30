<?php

namespace Domain\Model;

class DashboardConfig
{
    private $id;
    private $userId;
    private $role;
    private $viewId;
    private $title;
    private $description;
    private $filters = [];
    private $columns = [];
    private $sort = [];
    private $refreshIntervalSec;
    private $chartConfig = [];
    private $exportPreferences = [];
    private $pinned;
    private $isDefault;
    private $sharedWithRoles = [];
    private $createdAt;
    private $updatedAt;

    public function __construct(
        $id,
        $userId,
        string $role,
        string $viewId,
        string $title,
        string $description = null,
        array $filters = [],
        array $columns = [],
        array $sort = [],
        int $refreshIntervalSec = null,
        array $chartConfig = [],
        array $exportPreferences = [],
        bool $pinned = false,
        bool $isDefault = false,
        array $sharedWithRoles = [],
        \DateTime $createdAt = null,
        \DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->role = $role;
        $this->viewId = $viewId;
        $this->title = $title;
        $this->description = $description;
        $this->filters = $filters;
        $this->columns = $columns;
        $this->sort = $sort;
        $this->refreshIntervalSec = $refreshIntervalSec;
        $this->chartConfig = $chartConfig;
        $this->exportPreferences = $exportPreferences;
        $this->pinned = $pinned;
        $this->isDefault = $isDefault;
        $this->sharedWithRoles = $sharedWithRoles;
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

    public function getRole(): string
    {
        return $this->role;
    }

    public function getViewId(): string
    {
        return $this->viewId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
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

    public function getSort(): array
    {
        return $this->sort;
    }

    public function getRefreshIntervalSec(): ?int
    {
        return $this->refreshIntervalSec;
    }

    public function getChartConfig(): array
    {
        return $this->chartConfig;
    }

    public function getExportPreferences(): array
    {
        return $this->exportPreferences;
    }

    public function isPinned(): bool
    {
        return $this->pinned;
    }

    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function getSharedWithRoles(): array
    {
        return $this->sharedWithRoles;
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
