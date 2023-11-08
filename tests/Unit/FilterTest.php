<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\MovieRecommendation\Test\Unit;

use Faker\Factory;
use Faker\Generator;
use Kodkod\InterviewTask\MovieRecommendation\Filter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class FilterTest extends TestCase
{
    private readonly Generator $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
    }

    #[Test]
    public function nullConstructorArgs(): void
    {
        $filter = new Filter();

        self::assertNull($filter->getTitleStartsWith());
        self::assertNull($filter->getTitleHasMinWords());
        self::assertNull($filter->getTitleLengthIsEven());
    }

    #[Test]
    public function getTitleStartsWith(): void
    {
        $word = $this->faker->word();
        $filter = new Filter(titleStartsWith: $word);

        self::assertSame($word, $filter->getTitleStartsWith());
    }

    #[Test]
    public function getTitleHasMinWords(): void
    {
        $nbWords = $this->faker->randomNumber();
        $filter = new Filter(titleHasMinWords: $nbWords);

        self::assertSame($nbWords, $filter->getTitleHasMinWords());
    }

    #[Test]
    public function getTitleLengthIsEven(): void
    {
        $isEven = $this->faker->boolean();
        $filter = new Filter(titleLengthIsEven: $isEven);

        self::assertSame($isEven, $filter->getTitleLengthIsEven());
    }

    #[Test]
    #[DataProvider('provideFilterIsEmpty')]
    public function filterIsEmpty(Filter $filter, bool $expected): void
    {
        self::assertSame($expected, $filter->isEmpty());
    }

    /**
     * @return array[]
     */
    public static function provideFilterIsEmpty(): array
    {
        $faker = Factory::create();

        return [
            // #set #0
            [
                new Filter(),
                true,
            ],
            // #set #1
            [
                new Filter(titleStartsWith: $faker->word()),
                false,
            ],
            // #set #2
            [
                new Filter(titleLengthIsEven: true),
                false,
            ],
            // #set #3
            [
                new Filter(titleLengthIsEven: false),
                false,
            ],
            // #set #4
            [
                new Filter(titleHasMinWords: $faker->randomNumber()),
                false,
            ],
            // #set #5
            [
                new Filter(titleStartsWith: $faker->word(), titleHasMinWords: $faker->randomNumber()),
                false,
            ],
            // #set #6
            [
                new Filter(titleStartsWith: $faker->word(), titleLengthIsEven: false),
                false,
            ],
            // #set #7
            [
                new Filter(titleLengthIsEven: false, titleHasMinWords: $faker->randomNumber()),
                false,
            ],
        ];
    }
}
