<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\MovieRecommendation\Movie;

use Generator;

use Kodkod\InterviewTask\MovieRecommendation\Filter;

use function count;
use function dirname;
use function is_array;
use function mb_strlen;
use function preg_split;
use function str_starts_with;

use const PREG_SPLIT_NO_EMPTY;

/**
 * This clas mimics logic of any infrastructure-side repository for a database.
 */
class MovieRepository
{
    /**
     * @var array<Movie>
     */
    private array $movies;

    /**
     * @param Movie[]|null $movies Provide yours or pass null to load from the fixture file
     */
    public function __construct(private readonly MovieService $movieService, ?array $movies = null)
    {
        if (null === $movies) {
            $this->readMovies();
        } else {
            $this->movies = $movies;
        }
    }

    /**
     * @return Generator<Movie>
     */
    public function getAll(): Generator
    {
        foreach ($this->movies as $key => $movie) {
            // use yield to avoid unnecessary memory usage
            yield $key => $movie;
        }
    }

    public function countAll(): int
    {
        return count($this->movies);
    }

    /**
     * @return Generator<Movie>
     */
    public function getByFilter(Filter $filter): Generator
    {
        foreach ($this->getAll() as $movie) {
            $filterConditionsMet = true;
            if (null !== $filter->getTitleStartsWith()) {
                $filterConditionsMet &= str_starts_with(
                    mb_strtolower($movie->getTitle()),
                    mb_strtolower($filter->getTitleStartsWith())
                );
            }
            if (null !== $filter->getTitleLengthIsEven()) {
                $filterConditionsMet &= 0 === mb_strlen($movie->getTitle()) % 2;
            }
            if (null !== $filter->getTitleHasMinWords()) {
                $split = preg_split('/ /u', $movie->getTitle(), flags: PREG_SPLIT_NO_EMPTY);
                if (is_array($split)) {
                    $numOfWords = count($split);
                    $filterConditionsMet &= $numOfWords >= $filter->getTitleHasMinWords();
                } else {
                    $filterConditionsMet = 0;
                }
            }

            if (0 < $filterConditionsMet) {
                yield $movie;
            }
        }
    }

    private function readMovies(): void
    {
        /** @var array<string> $movies */
        $movies = [];

        include dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'fixtures'.DIRECTORY_SEPARATOR.'movies.php';

        foreach ($movies as $arrMovie) {
            $this->movies[] = $this->movieService->fromArray(['title' => $arrMovie]);
        }
    }
}
