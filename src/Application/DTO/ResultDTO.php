<?php

namespace Application\DTO;

class ResultDTO
{
    public $quizId;
    public $userId;
    public $evaluationType;
    public $submittedBy;
    public $evaluatedRole;
    public $sessionType;
    public $answers = [];
    public $score;
    public $timeSpent;
}
