<?php

namespace Domain\Model;

class CommercialQuiz
{
    private $id;
    private $label;
    private $level;
    private $brand;
    private $speciality;
    private $groupLevelMappingId;
    private $groupId;
    private $taskId;
    private $tags = [];
    private $questionIds = [];
    private $visibleForLevels = [];

    public function __construct(
        $id,
        string $label,
        string $level,
        string $brand,
        string $speciality,
        $groupLevelMappingId,
        $groupId,
        $taskId,
        array $tags,
        array $questionIds,
        array $visibleForLevels
    ) {
        $this->id = $id;
        $this->label = $label;
        $this->level = $level;
        $this->brand = $brand;
        $this->speciality = $speciality;
        $this->groupLevelMappingId = $groupLevelMappingId;
        $this->groupId = $groupId;
        $this->taskId = $taskId;
        $this->tags = $tags;
        $this->questionIds = $questionIds;
        $this->visibleForLevels = $visibleForLevels;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getSpeciality(): string
    {
        return $this->speciality;
    }

    public function getGroupLevelMappingId()
    {
        return $this->groupLevelMappingId;
    }

    public function getGroupId()
    {
        return $this->groupId;
    }

    public function getTaskId()
    {
        return $this->taskId;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function getQuestionIds(): array
    {
        return $this->questionIds;
    }

    public function getVisibleForLevels(): array
    {
        return $this->visibleForLevels;
    }
}
