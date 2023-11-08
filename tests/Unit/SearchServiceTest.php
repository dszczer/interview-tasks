<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\MovieRecommendation\Test\Unit;

use Faker\Factory;
use Faker\Generator as FakerGenerator;
use Generator;
use Kodkod\InterviewTask\MovieRecommendation\Movie\Movie;
use Kodkod\InterviewTask\MovieRecommendation\Movie\MovieRepository;
use Kodkod\InterviewTask\MovieRecommendation\SearchService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DependsOnClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use function count;
use function mb_strlen;
use function mb_strtolower;
use function str_starts_with;

final class SearchServiceTest extends TestCase
{
    private readonly FakerGenerator $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
    }

    #[Test]
    #[DataProvider('provideGetRandom')]
    public function getRandom(int $dataSetSize, int $limit, int $expected): void
    {
        $dataSet = [];
        for ($iteration = 0; $iteration < $dataSetSize; $iteration++) {
            $dataSet[] = new Movie($this->faker->sentence(3));
        }

        $movieRepositoryMock = $this->createMock(MovieRepository::class);
        $movieRepositoryMock
            ->method('getAll')
            ->willReturnCallback(function () use ($dataSet): Generator {
                foreach ($dataSet as $key => $item) {
                    yield $key => $item;
                }
            });
        $movieRepositoryMock
            ->method('countAll')
            ->willReturn(count($dataSet));
        $service = new SearchService($movieRepositoryMock);

        $result = iterator_to_array($service->getRandom($limit));

        self::assertLessThanOrEqual($expected, count($result));
        foreach ($result as $item) {
            self::assertInstanceOf(Movie::class, $item);
        }
    }

    public static function provideGetRandom(): array
    {
        return [
            // set #0
            [
                10,
                3,
                3,
            ],
            // set #1
            [
                18,
                2,
                2,
            ],
            // set #2
            [
                2,
                3,
                2,
            ],
            // set #3
            [
                0,
                8,
                0,
            ],
            // set #4
            [
                17,
                7,
                7,
            ],
        ];
    }

    #[Test]
    #[DataProvider('provideGetStartingWithWAndEvenOnly')]
    #[DependsOnClass(FilterTest::class)]
    public function getStartingWithWAndEvenOnly(int $dataSetSize): void
    {
        $dataSet = [];
        $expectedResultCount = 0;

        for ($iteration = 0; $iteration < $dataSetSize; $iteration++) {
            $title = (string) $this->faker->words($this->faker->numberBetween(1, 3), true);
            $movie = $this->createMock(Movie::class);
            $movie->method('getTitle')->willReturn($title);
            $dataSet[] = $movie;

            if (str_starts_with(mb_strtolower($title), 'w') && 0 === mb_strlen($title) % 2) {
                $expectedResultCount++;
            }
        }

        $movieRepositoryMock = $this->createMock(MovieRepository::class);
        $movieRepositoryMock
            ->method('getByFilter')
            ->willReturnCallback(function () use ($dataSet): Generator {
                /** @var Movie $item */
                foreach ($dataSet as $item) {
                    $title = $item->getTitle();
                    if (str_starts_with(mb_strtolower($title), 'w') && (0 === mb_strlen($title) % 2)) {
                        yield $item;
                    }
                }
            });
        $service = new SearchService($movieRepositoryMock);

        $result = iterator_to_array($service->getStartingWithWAndEvenOnly());

        self::assertCount($expectedResultCount, $result);
        foreach ($result as $item) {
            self::assertInstanceOf(Movie::class, $item);
        }
    }

    public static function provideGetStartingWithWAndEvenOnly(): array
    {
        return [[0], [1], [2], [3], [4], [5], [7], [11], [13], [17], [23], [27], [37], [63]];
    }

    #[Test]
    #[DataProvider('provideGetAllWithMultipleTitleWords')]
    #[DependsOnClass(FilterTest::class)]
    public function getAllWithMultipleTitleWords(int $dataSetSize): void
    {
        $dataSet = [];
        $expectedResultCount = 0;

        for ($iteration = 0; $iteration < $dataSetSize; $iteration++) {
            $title = (string) $this->faker->words($this->faker->numberBetween(1, 5), true);
            $movie = $this->createMock(Movie::class);
            $movie->method('getTitle')->willReturn($title);
            $dataSet[] = $movie;

            if (1 < count(preg_split('/ /u', $title, flags: PREG_SPLIT_NO_EMPTY))) {
                $expectedResultCount++;
            }
        }

        $movieRepositoryMock = $this->createMock(MovieRepository::class);
        $movieRepositoryMock
            ->method('getByFilter')
            ->willReturnCallback(function () use ($dataSet): Generator {
                /** @var Movie $item */
                foreach ($dataSet as $item) {
                    $title = $item->getTitle();
                    if (1 < count(preg_split('/ /u', $title, flags: PREG_SPLIT_NO_EMPTY))) {
                        yield $item;
                    }
                }
            });
        $service = new SearchService($movieRepositoryMock);

        $result = iterator_to_array($service->getAllWithMultipleTitleWords());

        self::assertCount($expectedResultCount, $result);
        foreach ($result as $item) {
            self::assertInstanceOf(Movie::class, $item);
        }
    }

    public static function provideGetAllWithMultipleTitleWords(): array
    {
        return [[0], [1], [2], [3], [4], [5], [7], [11], [13], [17], [23], [27], [37], [63]];
    }
}
