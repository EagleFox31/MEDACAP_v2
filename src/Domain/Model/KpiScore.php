<?php

namespace Domain\Model;

class KpiScore
{
    private $id;
    private $context = [];
    private $averages = [];
    private $masteryRate;

    public function __construct($id, array $context, array $averages, float $masteryRate)
    {
        $this->id = $id;
        $this->context = $context;
        $this->averages = $averages;
        $this->masteryRate = $masteryRate;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getContext(): array
    {
        return $this->context;
    }

    public function getAverages(): array
    {
        return $this->averages;
    }

    public function getMasteryRate(): float
    {
        return $this->masteryRate;
    }
}
