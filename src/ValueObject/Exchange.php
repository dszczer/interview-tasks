<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\CurrencyExchange\ValueObject;

use InvalidArgumentException;
use Kodkod\InterviewTask\CurrencyExchange\FloatUtil;

final readonly class Exchange
{
    public function __construct(
        private CurrencyValue $sellValue,
        private CurrencyValue $buyValue,
        private float $exchangeRate
    ) {
        $this->selfValidate();
    }

    public function getExchangeRate(): float
    {
        return $this->exchangeRate;
    }

    public function getSellValue(): CurrencyValue
    {
        return $this->sellValue;
    }

    public function getBuyValue(): CurrencyValue
    {
        return $this->buyValue;
    }

    private function selfValidate(): void
    {
        if ($this->getSellValue()->getCurrency()->name === $this->getBuyValue()->getCurrency()->name) {
            throw new InvalidArgumentException('Given currencies cannot be the same');
        }
        if ($this->exchangeRate < .0 || FloatUtil::isEqual($this->exchangeRate, .0)) {
            throw new InvalidArgumentException('"exchangeRate" must be a non-zero positive value');
        }
        if ($this->sellValue->getValue() < .0 || FloatUtil::isEqual($this->sellValue->getValue(), .0)) {
            throw new InvalidArgumentException('"sellValue" must be a non-zero positive value');
        }
        if ($this->buyValue->getValue() < .0 || FloatUtil::isEqual($this->buyValue->getValue(), .0)) {
            throw new InvalidArgumentException('"buyValue" must be a non-zero positive value');
        }
        if (!FloatUtil::isEqual($this->exchangeRate, $this->sellValue->getValue() / $this->buyValue->getValue())) {
            throw new InvalidArgumentException(sprintf('Given exchange rate "%f" is invalid for given sell "%f" and buy "%f" values', $this->exchangeRate, $this->sellValue->getValue(), $this->buyValue->getValue()));
        }
    }
}
