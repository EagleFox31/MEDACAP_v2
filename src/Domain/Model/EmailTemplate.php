<?php

namespace Domain\Model;

class EmailTemplate
{
    private $id;
    private $code;
    private $label;
    private $subject;
    private $body;
    private $variables = [];
    private $recipients = [];
    private $active;
    private $trigger;

    public function __construct(
        $id,
        string $code,
        string $label,
        string $subject,
        string $body,
        array $variables,
        array $recipients,
        bool $active,
        string $trigger
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->label = $label;
        $this->subject = $subject;
        $this->body = $body;
        $this->variables = $variables;
        $this->recipients = $recipients;
        $this->active = $active;
        $this->trigger = $trigger;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getVariables(): array
    {
        return $this->variables;
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getTrigger(): string
    {
        return $this->trigger;
    }
}
