<?php

namespace App;

class ReadMe implements \Stringable
{
    private function __construct(
        private string $content
    ) {
    }

    public function updateGlossary(string $glossary): self
    {
        $this->pregReplace('glossary', $glossary, true);

        return $this;
    }

    private function pregReplace(string $sectionName, string $replaceWith, bool $enforceNewLines = false): void
    {
        if (!$enforceNewLines) {
            $this->content = preg_replace(
                sprintf('/<!--START_SECTION:%s-->[\s\S]+<!--END_SECTION:%s-->/', $sectionName, $sectionName),
                sprintf('<!--START_SECTION:%s-->%s<!--END_SECTION:%s-->', $sectionName, $replaceWith, $sectionName),
                $this->content
            );

            return;
        }

        $this->content = preg_replace(
            sprintf('/<!--START_SECTION:%s-->[\s\S]+<!--END_SECTION:%s-->/', $sectionName, $sectionName),
            implode("\n", [
                sprintf('<!--START_SECTION:%s-->', $sectionName),
                $replaceWith,
                sprintf('<!--END_SECTION:%s-->', $sectionName),
            ]),
            $this->content
        );
    }

    public function __toString(): string
    {
        return $this->content;
    }

    public static function fromPathToReadMe(string $path): self
    {
        return new self(file_get_contents($path));
    }
}
