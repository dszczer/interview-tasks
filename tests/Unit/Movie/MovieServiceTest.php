<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\MovieRecommendation\Test\Unit\Movie;

use Faker\Factory;
use Faker\Generator;
use Kodkod\InterviewTask\MovieRecommendation\Movie\Movie;
use Kodkod\InterviewTask\MovieRecommendation\Movie\MovieService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class MovieServiceTest extends TestCase
{
    private readonly Generator $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
    }

    #[Test]
    public function fromArray(): void
    {
        $service = new MovieService();

        for ($trials = 1; $trials <= 50; $trials++) {
            $movieArr = [
                'title' => $this->faker->sentence(4),
            ];

            $movie = $service->fromArray($movieArr);

            self::assertInstanceOf(Movie::class, $movie);
            self::assertSame($movieArr['title'], $movie->getTitle());
        }
    }
}
