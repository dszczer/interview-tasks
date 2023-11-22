<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\CurrencyExchange\Service\CurrencyExchangeRate;

use Kodkod\InterviewTask\CurrencyExchange\Specification\CurrencySpecification;

interface CurrencyExchangeRateServiceInterface
{
    public function getRate(CurrencySpecification $from, CurrencySpecification $to): float;
}
