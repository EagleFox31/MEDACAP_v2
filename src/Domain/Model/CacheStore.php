<?php

namespace Domain\Model;

class CacheStore
{
    private $id;
    private $key;
    private $value;
    private $expiresAt;

    public function __construct($id, string $key, $value, ?\DateTime $expiresAt)
    {
        $this->id = $id;
        $this->key = $key;
        $this->value = $value;
        $this->expiresAt = $expiresAt;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getExpiresAt(): ?\DateTime
    {
        return $this->expiresAt;
    }
}
