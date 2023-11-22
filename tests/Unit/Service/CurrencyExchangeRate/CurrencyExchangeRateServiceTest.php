<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\CurrencyExchange\Test\Unit\Service\CurrencyExchangeRate;

use InvalidArgumentException;
use Kodkod\InterviewTask\CurrencyExchange\Dto\CurrencyExchangeRateDto;
use Kodkod\InterviewTask\CurrencyExchange\Exception\UnhandledCurrencyExchangeException;
use Kodkod\InterviewTask\CurrencyExchange\Service\CurrencyExchangeRate\CurrencyExchangeRateLoaderInterface;
use Kodkod\InterviewTask\CurrencyExchange\Service\CurrencyExchangeRate\CurrencyExchangeRateService;
use Kodkod\InterviewTask\CurrencyExchange\Specification\CurrencySpecification;
use Kodkod\InterviewTask\CurrencyExchange\Test\FakerTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use const PHP_FLOAT_EPSILON;

final class CurrencyExchangeRateServiceTest extends TestCase
{
    use FakerTrait;

    private const FLOAT_DELTA = 2 * PHP_FLOAT_EPSILON;

    private CurrencyExchangeRateLoaderInterface|MockObject $mockCurrencyExchangeRateLoader;

    /** @var CurrencyExchangeRateDto[] */
    private array $currencyExchangeRateDtoFixtures;

    protected function setUp(): void
    {
        $this->currencyExchangeRateDtoFixtures = self::loadCurrencyExchangeRateDtoFixtures();
        $this->mockCurrencyExchangeRateLoader = $this->createMock(CurrencyExchangeRateLoaderInterface::class);
        $this->mockCurrencyExchangeRateLoader
            ->method('load')
            ->willReturn($this->currencyExchangeRateDtoFixtures);
    }

    #[Test]
    public function getRate(): void
    {
        $service = new CurrencyExchangeRateService($this->mockCurrencyExchangeRateLoader);

        $result = $service->getRate(CurrencySpecification::EUR, CurrencySpecification::GBP);
        self::assertEqualsWithDelta($this->currencyExchangeRateDtoFixtures[0]->rate, $result, self::FLOAT_DELTA);

        $result = $service->getRate(CurrencySpecification::GBP, CurrencySpecification::EUR);
        self::assertEqualsWithDelta($this->currencyExchangeRateDtoFixtures[1]->rate, $result, self::FLOAT_DELTA);
    }

    #[Test]
    public function getRateForSameCurrencyThrowsException(): void
    {
        $service = new CurrencyExchangeRateService($this->mockCurrencyExchangeRateLoader);

        self::expectException(InvalidArgumentException::class);

        $service->getRate(CurrencySpecification::EUR, CurrencySpecification::EUR);
    }

    #[Test]
    public function getRateForUnhandledCurrenciesThrowsException(): void
    {
        $mockCurrencyExchangeRateLoader = $this->createMock(CurrencyExchangeRateLoaderInterface::class);
        $mockCurrencyExchangeRateLoader
            ->method('load')
            ->willReturn([]);
        $service = new CurrencyExchangeRateService($mockCurrencyExchangeRateLoader);

        self::expectException(UnhandledCurrencyExchangeException::class);

        $service->getRate(CurrencySpecification::EUR, CurrencySpecification::GBP);
    }

    /**
     * @return CurrencyExchangeRateDto[]
     */
    private static function loadCurrencyExchangeRateDtoFixtures(): array
    {
        $faker = self::getFakerStatic();

        return [
            new CurrencyExchangeRateDto('EUR', 'GBP', $faker->randomFloat(min: .1, max: 5.0)),
            new CurrencyExchangeRateDto('GBP', 'EUR', $faker->randomFloat(min: .1, max: 5.0)),
        ];
    }
}
