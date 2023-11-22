<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\CurrencyExchange\Service\CurrencyExchangeRate;

use Kodkod\InterviewTask\CurrencyExchange\Dto\CurrencyExchangeRateDto;

final readonly class CurrencyExchangeRateLoader implements CurrencyExchangeRateLoaderInterface
{
    public function load(): array
    {
        /*
         * These should be loaded from external source like database,
         * but to keep code simple, I placed those values here.
         */
        return [
            new CurrencyExchangeRateDto('EUR', 'GBP', 1.5678),
            new CurrencyExchangeRateDto('GBP', 'EUR', 1.5432),
        ];
    }
}
