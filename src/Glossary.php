<?php

namespace App;

class Glossary
{
    public const ZERO_TO_NINE_SECTION = '0-9';
    private readonly array $terms;

    private function __construct(
        Term ...$terms
    )
    {
        $this->terms = $terms;
    }

    public function getSections(): array
    {
        $header = array_unique(array_map(fn(Term $term) => $term->getSection(), $this->terms));
        sort($header);

        // First item will be 0-9, push this one to the end.
        $first = array_shift($header);

        return [...$header, $first];
    }

    public function getTermsForSection(string $section): array
    {
        $terms = array_filter(
            $this->terms,
            fn(Term $term) => strtoupper($term->getSection()) === $section,
        );

        usort($terms, fn(Term $a, Term $b) => strtolower($a->getName()) <=> strtolower($b->getName()));

        return $terms;
    }

    public static function fromPathToJsonFile(string $path): self
    {
        $terms = array_map(fn(array $item) => Term::fromNameAndDescription(
            $item['name'],
            $item['description']
        ), json_decode(file_get_contents($path), true));

        return new self(...$terms);
    }
}