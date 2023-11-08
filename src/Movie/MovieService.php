<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\MovieRecommendation\Movie;

class MovieService
{
    /**
     * @param array<string, string> $arrMovie
     */
    public function fromArray(array $arrMovie): Movie
    {
        return new Movie($arrMovie['title']);
    }
}
