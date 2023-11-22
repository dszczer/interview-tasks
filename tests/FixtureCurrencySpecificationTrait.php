<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\CurrencyExchange\Test;

use Kodkod\InterviewTask\CurrencyExchange\Specification\CurrencySpecification;

trait FixtureCurrencySpecificationTrait
{
    use FakerTrait;

    private function getRandomCurrencySpecification(): CurrencySpecification
    {
        return $this->getFaker()->randomElement(CurrencySpecification::cases());
    }
}
