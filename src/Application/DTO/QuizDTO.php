<?php

namespace Application\DTO;

class QuizDTO
{
    public $id;
    public $label;
    public $level;
    public $brand;
    public $speciality;
    public $groupLevelMappingId;
    public $groupId;
    public $taskId;
    public $tags = [];
    public $questionIds = [];
}
