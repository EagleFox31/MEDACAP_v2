<?php

namespace Domain\Model;

class GroupLevelMapping
{
    private $id;
    private $groupId;
    private $levelId;

    public function __construct($id, $groupId, $levelId)
    {
        $this->id = $id;
        $this->groupId = $groupId;
        $this->levelId = $levelId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getGroupId()
    {
        return $this->groupId;
    }

    public function getLevelId()
    {
        return $this->levelId;
    }
}
