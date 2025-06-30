<?php

namespace Domain\Model;

class CommercialUser
{
    private $id;
    private $firstName;
    private $lastName;
    private $filiale;
    private $agence;
    private $level;
    private $department;
    private $brandsByLevel = [];
    private $managerId;
    private $passwordHash;
    private $visiblePassword;

    public function __construct(
        $id,
        string $firstName,
        string $lastName,
        string $filiale,
        string $agence,
        string $level,
        string $department,
        array $brandsByLevel,
        string $managerId = null,
        string $passwordHash = null,
        string $visiblePassword = null
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->filiale = $filiale;
        $this->agence = $agence;
        $this->level = $level;
        $this->department = $department;
        $this->brandsByLevel = $brandsByLevel;
        $this->managerId = $managerId;
        $this->passwordHash = $passwordHash;
        $this->visiblePassword = $visiblePassword;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFiliale(): string
    {
        return $this->filiale;
    }

    public function getAgence(): string
    {
        return $this->agence;
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function getDepartment(): string
    {
        return $this->department;
    }

    public function getBrandsByLevel(): array
    {
        return $this->brandsByLevel;
    }

    public function getManagerId()
    {
        return $this->managerId;
    }

    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    public function getVisiblePassword()
    {
        return $this->visiblePassword;
    }
}
