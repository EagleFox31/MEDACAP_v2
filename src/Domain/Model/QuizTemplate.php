<?php

namespace Domain\Model;

class QuizTemplate
{
    private $id;
    private $label;
    private $description;
    private $niveauCible;
    private $departement;
    private $groupId;
    private $brandId;
    private $visibleForLevels = [];
    private $taskIds = [];
    private $tagDistribution = [];
    private $questionCount;
    private $selectionMode;
    private $randomizeOrder;
    private $allowMarking;
    private $allowReview;
    private $templateType;
    private $active;
    private $createdAt;
    private $createdBy;
    private $usageStats = [];

    public function __construct(
        $id,
        string $label,
        string $description,
        string $niveauCible,
        string $departement,
        $groupId,
        $brandId,
        array $visibleForLevels,
        array $taskIds,
        array $tagDistribution,
        int $questionCount,
        string $selectionMode,
        bool $randomizeOrder,
        bool $allowMarking,
        bool $allowReview,
        string $templateType,
        bool $active,
        \DateTime $createdAt,
        $createdBy = null,
        array $usageStats = []
    ) {
        $this->id = $id;
        $this->label = $label;
        $this->description = $description;
        $this->niveauCible = $niveauCible;
        $this->departement = $departement;
        $this->groupId = $groupId;
        $this->brandId = $brandId;
        $this->visibleForLevels = $visibleForLevels;
        $this->taskIds = $taskIds;
        $this->tagDistribution = $tagDistribution;
        $this->questionCount = $questionCount;
        $this->selectionMode = $selectionMode;
        $this->randomizeOrder = $randomizeOrder;
        $this->allowMarking = $allowMarking;
        $this->allowReview = $allowReview;
        $this->templateType = $templateType;
        $this->active = $active;
        $this->createdAt = $createdAt;
        $this->createdBy = $createdBy;
        $this->usageStats = $usageStats;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getNiveauCible(): string
    {
        return $this->niveauCible;
    }

    public function getDepartement(): string
    {
        return $this->departement;
    }

    public function getGroupId()
    {
        return $this->groupId;
    }

    public function getBrandId()
    {
        return $this->brandId;
    }

    public function getVisibleForLevels(): array
    {
        return $this->visibleForLevels;
    }

    public function getTaskIds(): array
    {
        return $this->taskIds;
    }

    public function getTagDistribution(): array
    {
        return $this->tagDistribution;
    }

    public function getQuestionCount(): int
    {
        return $this->questionCount;
    }

    public function getSelectionMode(): string
    {
        return $this->selectionMode;
    }

    public function shouldRandomizeOrder(): bool
    {
        return $this->randomizeOrder;
    }

    public function isAllowMarking(): bool
    {
        return $this->allowMarking;
    }

    public function isAllowReview(): bool
    {
        return $this->allowReview;
    }

    public function getTemplateType(): string
    {
        return $this->templateType;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function getUsageStats(): array
    {
        return $this->usageStats;
    }
}
