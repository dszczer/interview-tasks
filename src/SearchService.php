<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\MovieRecommendation;

use Generator;
use Kodkod\InterviewTask\MovieRecommendation\Movie\Movie;
use Kodkod\InterviewTask\MovieRecommendation\Movie\MovieRepository;

class SearchService
{
    private const RANDOM_DEFAULT = 3;

    public function __construct(private readonly MovieRepository $movieRepository)
    {
    }

    /**
     * Implementation of: Return 3 random movie titles.
     *
     * @return Generator<Movie>
     */
    public function getRandom(int $amount = self::RANDOM_DEFAULT): Generator
    {
        // this reflects keys of $movies
        /** @var int[] $shuffledIndices */
        $shuffledIndices = range(0, $this->movieRepository->countAll() - 1);
        // randomly shuffle them and just pick x first items - easy and uniquely randomized array
        shuffle($shuffledIndices);

        for ($shuffleKeyIdx = 0; $shuffleKeyIdx < $amount; $shuffleKeyIdx++) {
            if (!isset($shuffledIndices[$shuffleKeyIdx])) {
                // less available items than requested, exit
                return;
            }

            foreach ($this->movieRepository->getAll() as $movieKey => $movie) {
                if ($movieKey === $shuffledIndices[$shuffleKeyIdx]) {
                    yield $movie;
                    // get next item by $shuffledIndices
                    break;
                }
            }
        }
    }

    /**
     * Implementation of: Return all titles starting with *W* letter, but if amount of title's character is even only.
     *
     * @return Generator<Movie>
     */
    public function getStartingWithWAndEvenOnly(): Generator
    {
        $filter = new Filter(titleStartsWith: 'W', titleLengthIsEven: true);

        yield from $this->movieRepository->getByFilter($filter);
    }

    /**
     * Implementation of: Return all titles which contains more than one word.
     *
     * @return Generator<Movie>
     */
    public function getAllWithMultipleTitleWords(): Generator
    {
        $filter = new Filter(titleHasMinWords: 2);

        yield from $this->movieRepository->getByFilter($filter);
    }
}
