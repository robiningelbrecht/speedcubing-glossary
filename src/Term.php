<?php

namespace App;

class Term
{
    private function __construct(
        private readonly string $name,
        private readonly string $description
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCategory(): string
    {
        return strtoupper(substr($this->name, 0, 1));
    }

    public static function fromNameAndDescription(
        string $name,
        string $description
    ): self{
        return new self($name, $description);
    }
}