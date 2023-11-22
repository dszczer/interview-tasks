<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\CurrencyExchange\Test\Unit\Service;

use Kodkod\InterviewTask\CurrencyExchange\Service\ExchangeService;
use Kodkod\InterviewTask\CurrencyExchange\Specification\CurrencySpecification;
use Kodkod\InterviewTask\CurrencyExchange\ValueObject\CurrencyValue;
use Kodkod\InterviewTask\CurrencyExchange\ValueObject\Exchange;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

use const PHP_FLOAT_EPSILON;

final class ExchangeServiceTest extends TestCase
{
    private const RATE_EUR_GBP = 1.5678;
    private const RATE_GBP_EUR = 1.5432;
    private const EXCHANGE_FEE = .01;
    private const FLOAT_DELTA = 2 * PHP_FLOAT_EPSILON;

    private readonly ExchangeService $service;

    protected function setUp(): void
    {
        $this->service = $this->createService();
    }

    #[Test]
    #[DataProvider('provideCalculateBuyerReceivable')]
    public function calculateBuyerReceivable(Exchange $givenExchange, CurrencyValue $expected): void
    {
        $result = $this->service->calculateBuyerReceivable($givenExchange);

        self::assertInstanceOf(CurrencyValue::class, $result);
        self::assertEqualsWithDelta($result->getValue(), $expected->getValue(), self::FLOAT_DELTA);
        self::assertSame($result->getCurrency()->name, $expected->getCurrency()->name);
    }

    public static function provideCalculateBuyerReceivable(): array
    {
        return [
            // set #0, Customer buy 100 GBP for EUR
            [
                new Exchange(
                    new CurrencyValue(CurrencySpecification::EUR, 100.0),
                    new CurrencyValue(CurrencySpecification::GBP, 100.0 / self::RATE_EUR_GBP),
                    self::RATE_EUR_GBP
                ),
                new CurrencyValue(
                    CurrencySpecification::GBP,
                    (100.0 - 100.0 * self::EXCHANGE_FEE) / self::RATE_EUR_GBP
                ),
            ],
            // set #1, Customer buy 100 EUR for GBP
            [
                new Exchange(
                    new CurrencyValue(CurrencySpecification::GBP, 100.0),
                    new CurrencyValue(CurrencySpecification::EUR, 100.0 / self::RATE_GBP_EUR),
                    self::RATE_GBP_EUR
                ),
                new CurrencyValue(
                    CurrencySpecification::EUR,
                    (100.0 - 100.0 * self::EXCHANGE_FEE) / self::RATE_GBP_EUR
                ),
            ],
        ];
    }

    #[Test]
    #[DataProvider('provideCalculateSellerReceivable')]
    public function calculateSellerReceivable(Exchange $givenExchange, CurrencyValue $expected): void
    {
        $result = $this->service->calculateSellerReceivable($givenExchange);

        self::assertInstanceOf(CurrencyValue::class, $result);
        self::assertEqualsWithDelta($result->getValue(), $expected->getValue(), self::FLOAT_DELTA);
        self::assertSame($result->getCurrency()->name, $expected->getCurrency()->name);
    }

    public static function provideCalculateSellerReceivable(): array
    {
        return [
            // set #0, Customer sell 100 EUR for GBP
            [
                new Exchange(
                    new CurrencyValue(CurrencySpecification::EUR, 100.0),
                    new CurrencyValue(CurrencySpecification::GBP, 100.0 / self::RATE_EUR_GBP),
                    self::RATE_EUR_GBP
                ),
                new CurrencyValue(CurrencySpecification::EUR, 100.0 + 100.0 * self::EXCHANGE_FEE),
            ],
            // set #1, Customer sell 100 GPB for EUR
            [
                new Exchange(
                    new CurrencyValue(CurrencySpecification::GBP, 100.0),
                    new CurrencyValue(CurrencySpecification::EUR, 100.0 / self::RATE_GBP_EUR),
                    self::RATE_GBP_EUR
                ),
                new CurrencyValue(CurrencySpecification::GBP, 100.0 + 100.0 * self::EXCHANGE_FEE),
            ],
        ];
    }

    #[Test]
    #[DataProvider('provideCalculateBuyerFee')]
    public function calculateBuyerFee(Exchange $givenExchange, CurrencyValue $expected): void
    {
        $result = $this->service->calculateBuyerFee($givenExchange);

        self::assertInstanceOf(CurrencyValue::class, $result);
        self::assertEqualsWithDelta($result->getValue(), $expected->getValue(), self::FLOAT_DELTA);
        self::assertSame($result->getCurrency()->name, $expected->getCurrency()->name);
    }

    public static function provideCalculateBuyerFee(): array
    {
        return [
            // set #0, Customer buy 100 GBP for EUR
            [
                new Exchange(
                    new CurrencyValue(CurrencySpecification::EUR, 100.0),
                    new CurrencyValue(CurrencySpecification::GBP, 100.0 / self::RATE_EUR_GBP),
                    self::RATE_EUR_GBP
                ),
                new CurrencyValue(CurrencySpecification::GBP, 100.0 * self::EXCHANGE_FEE / self::RATE_EUR_GBP),
            ],
            // set #1, Customer buy 100 EUR for GBP
            [
                new Exchange(
                    new CurrencyValue(CurrencySpecification::GBP, 100.0),
                    new CurrencyValue(CurrencySpecification::EUR, 100.0 / self::RATE_GBP_EUR),
                    self::RATE_GBP_EUR
                ),
                new CurrencyValue(CurrencySpecification::EUR, 100.0 * self::EXCHANGE_FEE / self::RATE_GBP_EUR),
            ],
        ];
    }

    #[Test]
    #[DataProvider('provideCalculateSellerFee')]
    public function calculateSellerFee(Exchange $givenExchange, CurrencyValue $expected): void
    {
        $result = $this->service->calculateSellerFee($givenExchange);

        self::assertInstanceOf(CurrencyValue::class, $result);
        self::assertEqualsWithDelta($result->getValue(), $expected->getValue(), self::FLOAT_DELTA);
        self::assertSame($result->getCurrency()->name, $expected->getCurrency()->name);
    }

    public static function provideCalculateSellerFee(): array
    {
        return [
            // set #0, Customer sell 100 EUR for GBP
            [
                new Exchange(
                    new CurrencyValue(CurrencySpecification::EUR, 100.0),
                    new CurrencyValue(CurrencySpecification::GBP, 100.0 / self::RATE_EUR_GBP),
                    self::RATE_EUR_GBP
                ),
                new CurrencyValue(CurrencySpecification::EUR, 100.0 * self::EXCHANGE_FEE),
            ],
            // set #1, Customer sell 100 EUR for GBP
            [
                new Exchange(
                    new CurrencyValue(CurrencySpecification::GBP, 100.0),
                    new CurrencyValue(CurrencySpecification::EUR, 100.0 / self::RATE_GBP_EUR),
                    self::RATE_GBP_EUR
                ),
                new CurrencyValue(CurrencySpecification::GBP, 100.0 * self::EXCHANGE_FEE),
            ],
        ];
    }

    private function createService(): ExchangeService
    {
        return new ExchangeService(self::EXCHANGE_FEE);
    }
}
