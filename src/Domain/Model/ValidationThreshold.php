<?php

namespace Domain\Model;

class ValidationThreshold
{
    private $id;
    private $level;
    private $threshold;

    public function __construct($id, string $level, float $threshold)
    {
        $this->id = $id;
        $this->level = $level;
        $this->threshold = $threshold;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function getThreshold(): float
    {
        return $this->threshold;
    }
}
