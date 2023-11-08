<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Domain\Exception;

use InvalidArgumentException;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\Assessment;
use Throwable;

class PerformingEvaluationOnLockedAssessmentException extends InvalidArgumentException
{
    public function __construct(Assessment $assessment, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf(
            'Assessment with status "%s" is currently locked and cannot be modified.',
            $assessment->getStatus()->name
        );

        parent::__construct($message, $code, $previous);
    }
}
