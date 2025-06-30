<?php

namespace Domain\Model;

class Brand
{
    private $id;
    private $name;

    public function __construct($id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
