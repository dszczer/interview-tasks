<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\CurrencyExchange\Service;

use Kodkod\InterviewTask\CurrencyExchange\Service\CurrencyExchangeRate\CurrencyExchangeRateServiceInterface;
use Kodkod\InterviewTask\CurrencyExchange\ValueObject\CurrencyValue;
use Kodkod\InterviewTask\CurrencyExchange\ValueObject\Exchange;

final readonly class ExchangeFactory
{
    public function __construct(private CurrencyExchangeRateServiceInterface $currencyExchangeRateService)
    {
    }

    public function make(CurrencyValue $from, CurrencyValue $to): Exchange
    {
        $exchangeRate = $this->currencyExchangeRateService->getRate($from->getCurrency(), $to->getCurrency());

        return new Exchange($from, $to, $exchangeRate);
    }
}
