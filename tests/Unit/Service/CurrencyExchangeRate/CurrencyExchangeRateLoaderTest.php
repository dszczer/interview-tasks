<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\CurrencyExchange\Test\Unit\Service\CurrencyExchangeRate;

use Kodkod\InterviewTask\CurrencyExchange\Dto\CurrencyExchangeRateDto;
use Kodkod\InterviewTask\CurrencyExchange\Service\CurrencyExchangeRate\CurrencyExchangeRateLoader;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CurrencyExchangeRateLoaderTest extends TestCase
{
    #[Test]
    public function load(): void
    {
        $loader = new CurrencyExchangeRateLoader();

        $given = $loader->load();

        self::assertIsArray($given);
        foreach ($given as $dto) {
            self::assertInstanceOf(CurrencyExchangeRateDto::class, $dto);
        }
    }
}
