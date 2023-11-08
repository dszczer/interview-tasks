<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Domain\Exception;

use InvalidArgumentException;
use Throwable;

class PerformingEvaluationOnExpiredAssessmentException extends InvalidArgumentException
{
    public function __construct(int $code = 0, ?Throwable $previous = null)
    {
        $message = 'Assessment is expired and cannot be modified.';

        parent::__construct($message, $code, $previous);
    }
}
