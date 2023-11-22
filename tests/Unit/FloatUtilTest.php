<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\CurrencyExchange\Test\Unit;

use Kodkod\InterviewTask\CurrencyExchange\FloatUtil;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use const PHP_FLOAT_EPSILON;

final class FloatUtilTest extends TestCase
{
    private const FLOAT_DELTA = PHP_FLOAT_EPSILON * 2;

    #[Test]
    #[DataProvider('provideIsEqual')]
    public function isEqual(float $givenA, float $givenB, bool $expected): void
    {
        $result = FloatUtil::isEqual($givenA, $givenB);

        self::assertEqualsWithDelta($expected, $result, self::FLOAT_DELTA);
    }

    public static function provideIsEqual(): array
    {
        return [
            // set #0
            [
                .0,
                .0,
                true,
            ],
            // set #1
            [
                PHP_FLOAT_EPSILON,
                PHP_FLOAT_EPSILON,
                true,
            ],
            // set #2
            [
                .0,
                PHP_FLOAT_EPSILON,
                true,
            ],
            // set #3
            [
                PHP_FLOAT_EPSILON,
                .0,
                true,
            ],
            // set #4
            [
                -PHP_FLOAT_EPSILON,
                PHP_FLOAT_EPSILON,
                false,
            ],
            // set #5
            [
                PHP_FLOAT_EPSILON,
                -PHP_FLOAT_EPSILON,
                false,
            ],
            // set #6
            [
                .0,
                self::FLOAT_DELTA,
                false,
            ],
            // set #7
            [
                -self::FLOAT_DELTA,
                .0,
                false,
            ],
        ];
    }
}
