<?php

namespace Domain\Model;

class ProfessionalTask
{
    private $id;
    private $code;
    private $label;
    private $description;
    private $groupId;
    private $tags = [];
    private $active;
    private $createdAt;
    private $updatedAt;
    private $createdBy;
    private $statistics = [];

    public function __construct(
        $id,
        string $code,
        string $label,
        string $description,
        $groupId,
        array $tags,
        bool $active,
        \DateTime $createdAt,
        \DateTime $updatedAt = null,
        $createdBy = null,
        array $statistics = []
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->label = $label;
        $this->description = $description;
        $this->groupId = $groupId;
        $this->tags = $tags;
        $this->active = $active;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->createdBy = $createdBy;
        $this->statistics = $statistics;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getGroupId()
    {
        return $this->groupId;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function getStatistics(): array
    {
        return $this->statistics;
    }
}
