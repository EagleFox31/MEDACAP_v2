<?php

namespace Domain\Model;

class CommercialScore
{
    private $id;
    private $userId;
    private $levels = [];

    public function __construct($id, $userId, array $levels)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->levels = $levels;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getLevels(): array
    {
        return $this->levels;
    }
}
