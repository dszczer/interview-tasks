<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\CurrencyExchange\ValueObject;

use Kodkod\InterviewTask\CurrencyExchange\Specification\CurrencySpecification;

final readonly class CurrencyValue
{
    public function __construct(private CurrencySpecification $currency, private float $value)
    {
    }

    public function getCurrency(): CurrencySpecification
    {
        return $this->currency;
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
