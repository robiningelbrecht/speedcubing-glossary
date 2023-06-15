<?php

namespace App;

class Glossary
{
    private readonly array $terms;

    private function __construct(
        Term ...$terms
    )
    {
        $this->terms = $terms;
    }

    public function getSections(): array
    {
        $header = array_unique(array_map(fn(Term $term) => $term->getCategory(), $this->terms));
        sort($header);

        return $header;
    }

    public function getTermsForSection(string $section): array
    {
        $terms = array_filter(
            $this->terms,
            fn(Term $term) => str_starts_with(strtoupper($term->getName()), $section)
        );
        usort($terms, fn(Term $a, Term $b) => strtolower($a->getName()) <=> strtolower($b->getName()));

        return $terms;
    }

    public static function fromJson(string $json): self
    {
        $terms = array_map(fn(array $item) => Term::fromNameAndDescription(
            $item['name'],
            $item['description']
        ), json_decode($json, true));

        return new self(...$terms);
    }
}