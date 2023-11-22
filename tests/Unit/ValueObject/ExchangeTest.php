<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\CurrencyExchange\Test\Unit\Service;

use InvalidArgumentException;
use Kodkod\InterviewTask\CurrencyExchange\Specification\CurrencySpecification;
use Kodkod\InterviewTask\CurrencyExchange\Test\FakerTrait;
use Kodkod\InterviewTask\CurrencyExchange\Test\FixtureCurrencySpecificationTrait;
use Kodkod\InterviewTask\CurrencyExchange\ValueObject\CurrencyValue;
use Kodkod\InterviewTask\CurrencyExchange\ValueObject\Exchange;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use const PHP_FLOAT_EPSILON;
use const PHP_FLOAT_MIN;

final class ExchangeTest extends TestCase
{
    use FakerTrait;
    use FixtureCurrencySpecificationTrait;

    private const FLOAT_DELTA = 2 * PHP_FLOAT_EPSILON;

    #[Test]
    public function getExchangeRate(): void
    {
        $fromValue = $this->getFaker()->randomFloat(min: 1);
        $toValue = $this->getFaker()->randomFloat(min: 1);
        $givenExchangeRate = self::calculateExchangeRate($fromValue, $toValue);

        $subject = new Exchange(
            new CurrencyValue(CurrencySpecification::GBP, $fromValue),
            new CurrencyValue(CurrencySpecification::EUR, $toValue),
            $givenExchangeRate
        );

        self::assertEqualsWithDelta($givenExchangeRate, $subject->getExchangeRate(), self::FLOAT_DELTA);
    }

    #[Test]
    public function getSellValue(): void
    {
        $fromValue = $this->getFaker()->randomFloat(min: 1);
        $toValue = $this->getFaker()->randomFloat(min: 1);
        $givenSellValue = new CurrencyValue(CurrencySpecification::GBP, $fromValue);

        $subject = new Exchange(
            $givenSellValue,
            new CurrencyValue(CurrencySpecification::EUR, $toValue),
            self::calculateExchangeRate($fromValue, $toValue)
        );

        self::assertSame($givenSellValue, $subject->getSellValue());
    }

    #[Test]
    public function getBuyValue(): void
    {
        $fromValue = $this->getFaker()->randomFloat(min: 1);
        $toValue = $this->getFaker()->randomFloat(min: 1);
        $givenBuyValue = new CurrencyValue(CurrencySpecification::GBP, $toValue);

        $subject = new Exchange(
            new CurrencyValue(CurrencySpecification::EUR, $fromValue),
            $givenBuyValue,
            self::calculateExchangeRate($fromValue, $toValue)
        );

        self::assertSame($givenBuyValue, $subject->getBuyValue());
    }

    #[Test]
    #[DataProvider('provideSelfValidate')]
    public function selfValidate(
        CurrencyValue $givenSellValue,
        CurrencyValue $givenBuyValue,
        float $givenExchangeRate,
        bool $expectException
    ): void {
        if ($expectException) {
            self::expectException(InvalidArgumentException::class);
        } else {
            self::expectNotToPerformAssertions();
        }

        new Exchange($givenSellValue, $givenBuyValue, $givenExchangeRate);
    }

    public static function provideSelfValidate(): array
    {
        return [
            // set #0
            self::createSetProvideSelfValidate(
                CurrencySpecification::EUR,
                CurrencySpecification::GBP,
                false
            ),
            // set #1
            self::createSetProvideSelfValidate(
                CurrencySpecification::GBP,
                CurrencySpecification::EUR,
                false
            ),
            // set #2
            self::createSetProvideSelfValidate(
                CurrencySpecification::EUR,
                CurrencySpecification::EUR,
                true
            ),
            // set #3
            self::createSetProvideSelfValidate(
                CurrencySpecification::GBP,
                CurrencySpecification::GBP,
                true
            ),
            // set #4
            [
                new CurrencyValue(CurrencySpecification::GBP, .0),
                new CurrencyValue(CurrencySpecification::EUR, self::getFakerStatic()->randomFloat(min: 1)),
                self::getFakerStatic()->randomFloat(min: 1),
                true,
            ],
            // set #5
            [
                new CurrencyValue(CurrencySpecification::GBP, self::getFakerStatic()->randomFloat(min: 1)),
                new CurrencyValue(CurrencySpecification::EUR, .0),
                self::getFakerStatic()->randomFloat(min: 1),
                true,
            ],
            // set #6
            [
                new CurrencyValue(CurrencySpecification::GBP, self::getFakerStatic()->randomFloat(min: 1)),
                new CurrencyValue(CurrencySpecification::EUR, self::getFakerStatic()->randomFloat(min: 1)),
                .0,
                true,
            ],
            // set #7
            [
                new CurrencyValue(
                    CurrencySpecification::GBP,
                    self::getFakerStatic()->randomFloat(min: PHP_FLOAT_MIN, max: -self::FLOAT_DELTA)
                ),
                new CurrencyValue(CurrencySpecification::EUR, self::getFakerStatic()->randomFloat(min: 1)),
                self::getFakerStatic()->randomFloat(min: 1),
                true,
            ],
            // set #8
            [
                new CurrencyValue(CurrencySpecification::GBP, self::getFakerStatic()->randomFloat(min: 1)),
                new CurrencyValue(
                    CurrencySpecification::EUR,
                    self::getFakerStatic()->randomFloat(min: PHP_FLOAT_MIN, max: -self::FLOAT_DELTA)
                ),
                self::getFakerStatic()->randomFloat(min: 1),
                true,
            ],
            // set #9
            [
                new CurrencyValue(CurrencySpecification::GBP, self::getFakerStatic()->randomFloat(min: 1)),
                new CurrencyValue(CurrencySpecification::EUR, self::getFakerStatic()->randomFloat(min: 1)),
                self::getFakerStatic()->randomFloat(min: PHP_FLOAT_MIN, max: -self::FLOAT_DELTA),
                true,
            ],
            // set #10
            [
                new CurrencyValue(CurrencySpecification::GBP, self::getFakerStatic()->randomFloat(min: 1)),
                new CurrencyValue(CurrencySpecification::EUR, self::getFakerStatic()->randomFloat(min: 1)),
                self::getFakerStatic()->randomFloat(min: 1),
                true,
            ],
        ];
    }

    private static function createSetProvideSelfValidate(
        CurrencySpecification $from,
        CurrencySpecification $to,
        bool $expectException
    ): array {
        $faker = self::getFakerStatic();
        $fromValue = $faker->randomFloat(min: 1);
        $toValue = $faker->randomFloat(min: 1);

        return [
            new CurrencyValue($from, $fromValue),
            new CurrencyValue($to, $toValue),
            self::calculateExchangeRate($fromValue, $toValue),
            $expectException,
        ];
    }

    private static function calculateExchangeRate(float $from, float $to): float
    {
        return $from / $to;
    }
}
