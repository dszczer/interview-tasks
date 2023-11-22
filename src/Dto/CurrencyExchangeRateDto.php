<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\CurrencyExchange\Dto;

final readonly class CurrencyExchangeRateDto
{
    public function __construct(public string $from, public string $to, public float $rate)
    {
    }
}
