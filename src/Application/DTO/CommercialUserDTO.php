<?php

namespace Application\DTO;

class CommercialUserDTO
{
    public $id;
    public $firstName;
    public $lastName;
    public $filiale;
    public $agence;
    public $level;
    public $department;
    public $brandsByLevel = [];
    public $managerId;
    public $passwordHash;
    public $visiblePassword;
}
