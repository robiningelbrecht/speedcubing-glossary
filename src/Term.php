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

    public function getSection(): string
    {
        $firstChar = substr($this->name, 0, 1);

        if (preg_match('/[a-zA-Z]/', $firstChar)) {
            return strtoupper($firstChar);
        }

        return Glossary::ZERO_TO_NINE_SECTION;
    }

    public static function fromNameAndDescription(
        string $name,
        string $description
    ): self
    {
        return new self($name, $description);
    }
}