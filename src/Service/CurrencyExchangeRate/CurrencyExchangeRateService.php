<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\CurrencyExchange\Service\CurrencyExchangeRate;

use InvalidArgumentException;
use Kodkod\InterviewTask\CurrencyExchange\Exception\UnhandledCurrencyExchangeException;
use Kodkod\InterviewTask\CurrencyExchange\Specification\CurrencySpecification;

use function strtoupper;

final readonly class CurrencyExchangeRateService implements CurrencyExchangeRateServiceInterface
{
    public function __construct(private CurrencyExchangeRateLoaderInterface $currencyExchangeRateLoader)
    {
    }

    /**
     * @throws UnhandledCurrencyExchangeException
     */
    public function getRate(CurrencySpecification $from, CurrencySpecification $to): float
    {
        if ($from->name === $to->name) {
            throw new InvalidArgumentException('Currencies "from" and "to" cannot be the same');
        }

        foreach ($this->currencyExchangeRateLoader->load() as $exchangeRateDto) {
            $entityFrom = CurrencySpecification::from(strtoupper($exchangeRateDto->from));
            $entityTo = CurrencySpecification::from(strtoupper($exchangeRateDto->to));

            if ($entityFrom->name === $from->name && $entityTo->name === $to->name) {
                return $exchangeRateDto->rate;
            }
        }

        throw new UnhandledCurrencyExchangeException($from, $to);
    }
}
