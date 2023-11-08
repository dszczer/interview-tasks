<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\MovieRecommendation\Movie;

class Movie
{
    public function __construct(private readonly string $title)
    {
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
