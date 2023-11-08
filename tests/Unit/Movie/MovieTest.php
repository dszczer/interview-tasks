<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\MovieRecommendation\Test\Unit\Movie;

use Faker\Factory;
use Faker\Generator;
use Kodkod\InterviewTask\MovieRecommendation\Movie\Movie;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function mt_rand;

final class MovieTest extends TestCase
{
    private readonly Generator $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
    }

    #[Test]
    public function getTitle(): void
    {
        $title = $this->faker->sentence(mt_rand(1, 4));
        $movie = new Movie($title);

        self::assertSame($title, $movie->getTitle());
    }
}
