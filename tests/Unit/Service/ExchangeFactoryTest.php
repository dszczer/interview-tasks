<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\CurrencyExchange\Test\Unit\Service;

use Kodkod\InterviewTask\CurrencyExchange\Service\CurrencyExchangeRate\CurrencyExchangeRateServiceInterface;
use Kodkod\InterviewTask\CurrencyExchange\Service\ExchangeFactory;
use Kodkod\InterviewTask\CurrencyExchange\Specification\CurrencySpecification;
use Kodkod\InterviewTask\CurrencyExchange\ValueObject\CurrencyValue;
use Kodkod\InterviewTask\CurrencyExchange\ValueObject\Exchange;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use const PHP_FLOAT_EPSILON;

final class ExchangeFactoryTest extends TestCase
{
    private const FLOAT_DELTA = 2 * PHP_FLOAT_EPSILON;

    private CurrencyExchangeRateServiceInterface|MockObject $mockCurrencyExchangeRateService;

    protected function setUp(): void
    {
        $this->mockCurrencyExchangeRateService = $this->createMock(CurrencyExchangeRateServiceInterface::class);
        $this->mockCurrencyExchangeRateService
            ->method('getRate')
            ->willReturn(1.5678, 1.5432);
    }

    #[Test]
    public function getRate(): void
    {
        $service = new ExchangeFactory($this->mockCurrencyExchangeRateService);

        $from = new CurrencyValue(CurrencySpecification::EUR, 100.0);
        $to = new CurrencyValue(CurrencySpecification::GBP, 100.0 / 1.5678);
        $result = $service->make($from, $to);

        self::assertInstanceOf(Exchange::class, $result);
        self::assertEqualsWithDelta(1.5678, $result->getExchangeRate(), self::FLOAT_DELTA);

        $from = new CurrencyValue(CurrencySpecification::GBP, 100.0);
        $to = new CurrencyValue(CurrencySpecification::EUR, 100.0 / 1.5432);
        $result = $service->make($from, $to);

        self::assertInstanceOf(Exchange::class, $result);
        self::assertEqualsWithDelta(1.5432, $result->getExchangeRate(), self::FLOAT_DELTA);
    }
}
