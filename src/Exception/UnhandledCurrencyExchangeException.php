<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\CurrencyExchange\Exception;

use Kodkod\InterviewTask\CurrencyExchange\Specification\CurrencySpecification;
use Throwable;
use UnhandledMatchError;

class UnhandledCurrencyExchangeException extends UnhandledMatchError
{
    public function __construct(
        CurrencySpecification $from,
        CurrencySpecification $to,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $message = sprintf('Unhandled currency exchange from "%s" to "%s"', $from->name, $to->name);

        parent::__construct($message, $code, $previous);
    }
}
