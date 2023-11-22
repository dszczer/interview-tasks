<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\CurrencyExchange\Test\Unit\Service;

use Kodkod\InterviewTask\CurrencyExchange\Test\FakerTrait;
use Kodkod\InterviewTask\CurrencyExchange\Test\FixtureCurrencySpecificationTrait;
use Kodkod\InterviewTask\CurrencyExchange\ValueObject\CurrencyValue;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use const PHP_FLOAT_EPSILON;

final class CurrencyValueTest extends TestCase
{
    use FakerTrait;
    use FixtureCurrencySpecificationTrait;

    #[Test]
    public function getCurrency(): void
    {
        $givenCurrency = $this->getRandomCurrencySpecification();

        $subject = new CurrencyValue($givenCurrency, $this->getFaker()->randomFloat());

        self::assertSame($givenCurrency, $subject->getCurrency());
    }

    #[Test]
    public function getValue(): void
    {
        $givenValue = $this->getFaker()->randomFloat();

        $subject = new CurrencyValue($this->getRandomCurrencySpecification(), $givenValue);

        self::assertEqualsWithDelta($givenValue, $subject->getValue(), 2 * PHP_FLOAT_EPSILON);
    }
}
