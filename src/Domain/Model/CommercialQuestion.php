<?php

namespace Domain\Model;

class CommercialQuestion
{
    private $id;
    private $label;
    private $tags = [];
    private $level;
    private $speciality;
    private $department;
    private $groupLevelMappingId;
    private $groupId;
    private $taskId;
    private $type; // factuel or declaratif
    private $statistics = [];

    public function __construct(
        $id,
        string $label,
        array $tags,
        string $level,
        string $speciality,
        string $department,
        $groupLevelMappingId,
        $groupId,
        $taskId,
        string $type,
        array $statistics = []
    ) {
        $this->id = $id;
        $this->label = $label;
        $this->tags = $tags;
        $this->level = $level;
        $this->speciality = $speciality;
        $this->department = $department;
        $this->groupLevelMappingId = $groupLevelMappingId;
        $this->groupId = $groupId;
        $this->taskId = $taskId;
        $this->type = $type;
        $this->statistics = $statistics;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function getSpeciality(): string
    {
        return $this->speciality;
    }

    public function getDepartment(): string
    {
        return $this->department;
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

    public function getType(): string
    {
        return $this->type;
    }

    public function getStatistics(): array
    {
        return $this->statistics;
    }
}
