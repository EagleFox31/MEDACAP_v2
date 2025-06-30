<?php

namespace Domain\Model;

class CronConfig
{
    private $id;
    private $name;
    private $schedule;
    private $active;

    public function __construct($id, string $name, string $schedule, bool $active)
    {
        $this->id = $id;
        $this->name = $name;
        $this->schedule = $schedule;
        $this->active = $active;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSchedule(): string
    {
        return $this->schedule;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
