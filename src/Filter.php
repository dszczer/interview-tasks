<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\MovieRecommendation;

class Filter
{
    public function __construct(
        private readonly ?string $titleStartsWith = null,
        private readonly ?bool $titleLengthIsEven = null,
        private readonly ?int $titleHasMinWords = null
    ) {
    }

    public function getTitleStartsWith(): ?string
    {
        return $this->titleStartsWith;
    }

    public function getTitleLengthIsEven(): ?bool
    {
        return $this->titleLengthIsEven;
    }

    public function getTitleHasMinWords(): ?int
    {
        return $this->titleHasMinWords;
    }

    public function isEmpty(): bool
    {
        return null === $this->titleStartsWith && null === $this->titleLengthIsEven && null === $this->titleHasMinWords;
    }
}
