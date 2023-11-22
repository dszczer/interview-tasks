<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\CurrencyExchange\Service;

use Kodkod\InterviewTask\CurrencyExchange\ValueObject\CurrencyValue;
use Kodkod\InterviewTask\CurrencyExchange\ValueObject\Exchange;

final readonly class ExchangeService
{
    public const DEFAULT_EXCHANGE_FEE = .01;

    public function __construct(private float $exchangeFee = self::DEFAULT_EXCHANGE_FEE)
    {
    }

    public function calculateBuyerReceivable(Exchange $exchange): CurrencyValue
    {
        $fee = $this->calculateBuyerFee($exchange);

        $receivable = $exchange->getBuyValue()->getValue() - $fee->getValue();

        return new CurrencyValue($exchange->getBuyValue()->getCurrency(), $receivable);
    }

    public function calculateSellerReceivable(Exchange $exchange): CurrencyValue
    {
        $fee = $this->calculateSellerFee($exchange);

        $receivable = $exchange->getSellValue()->getValue() + $fee->getValue();

        return new CurrencyValue($exchange->getSellValue()->getCurrency(), $receivable);
    }

    public function calculateBuyerFee(Exchange $exchange): CurrencyValue
    {
        return $this->calculateFee($exchange->getBuyValue());
    }

    public function calculateSellerFee(Exchange $exchange): CurrencyValue
    {
        return $this->calculateFee($exchange->getSellValue());
    }

    private function calculateFee(CurrencyValue $exchangeValue): CurrencyValue
    {
        return new CurrencyValue($exchangeValue->getCurrency(), $this->exchangeFee * $exchangeValue->getValue());
    }
}
