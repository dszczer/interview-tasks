<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\CurrencyExchange\Service\CurrencyExchangeRate;

use Kodkod\InterviewTask\CurrencyExchange\Dto\CurrencyExchangeRateDto;

interface CurrencyExchangeRateLoaderInterface
{
    /**
     * @return CurrencyExchangeRateDto[]
     */
    public function load(): array;
}
