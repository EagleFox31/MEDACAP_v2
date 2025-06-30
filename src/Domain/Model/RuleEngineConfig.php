<?php

namespace Domain\Model;

class RuleEngineConfig
{
    private $id;
    private $context;
    private $description;
    private $targetLevel;
    private $brandId;
    private $groupIds = [];
    private $taskTags = [];
    private $ruleType;
    private $active;
    private $priority;
    private $trigger;
    private $conditions = [];
    private $actions = [];
    private $validFrom;
    private $validTo;
    private $createdAt;
    private $createdBy;

    public function __construct(
        $id,
        string $context,
        string $description,
        string $targetLevel = null,
        $brandId = null,
        array $groupIds = [],
        array $taskTags = [],
        string $ruleType,
        bool $active,
        int $priority,
        string $trigger,
        array $conditions = [],
        array $actions = [],
        \DateTime $validFrom = null,
        \DateTime $validTo = null,
        \DateTime $createdAt,
        $createdBy = null
    ) {
        $this->id = $id;
        $this->context = $context;
        $this->description = $description;
        $this->targetLevel = $targetLevel;
        $this->brandId = $brandId;
        $this->groupIds = $groupIds;
        $this->taskTags = $taskTags;
        $this->ruleType = $ruleType;
        $this->active = $active;
        $this->priority = $priority;
        $this->trigger = $trigger;
        $this->conditions = $conditions;
        $this->actions = $actions;
        $this->validFrom = $validFrom;
        $this->validTo = $validTo;
        $this->createdAt = $createdAt;
        $this->createdBy = $createdBy;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getContext(): string
    {
        return $this->context;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTargetLevel()
    {
        return $this->targetLevel;
    }

    public function getBrandId()
    {
        return $this->brandId;
    }

    public function getGroupIds(): array
    {
        return $this->groupIds;
    }

    public function getTaskTags(): array
    {
        return $this->taskTags;
    }

    public function getRuleType(): string
    {
        return $this->ruleType;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getTrigger(): string
    {
        return $this->trigger;
    }

    public function getConditions(): array
    {
        return $this->conditions;
    }

    public function getActions(): array
    {
        return $this->actions;
    }

    public function getValidFrom(): ?\DateTime
    {
        return $this->validFrom;
    }

    public function getValidTo(): ?\DateTime
    {
        return $this->validTo;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}
