<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Domain\Exception;

use InvalidArgumentException;
use Throwable;

class NotNaturalIntegerValueException extends InvalidArgumentException
{
    public function __construct(?int $integer, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf('"%d" is not an natural integer value.', $integer);

        parent::__construct($message, $code, $previous);
    }
}
