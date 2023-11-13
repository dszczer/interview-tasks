<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Domain\Exception;

use DateTimeInterface;
use OutOfBoundsException;
use Throwable;

class DateTimeOutOfBoundsException extends OutOfBoundsException
{
    public function __construct(
        DateTimeInterface $dateTimeFrom,
        DateTimeInterface $dateTimeTo,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $message = sprintf(
            'Lower bound DateTime object "%s" is in the future against upper bound DateTime "%s".',
            $dateTimeFrom->format(DateTimeInterface::ATOM),
            $dateTimeTo->format(DateTimeInterface::ATOM)
        );

        parent::__construct($message, $code, $previous);
    }
}
