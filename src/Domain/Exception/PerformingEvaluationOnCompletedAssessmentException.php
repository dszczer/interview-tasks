<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Domain\Exception;

use InvalidArgumentException;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\Assessment;
use Throwable;

class PerformingEvaluationOnCompletedAssessmentException extends InvalidArgumentException
{
    public function __construct(Assessment $assessment, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf(
            'Assessment was completed at %s and cannot be modified.',
            $assessment->getCompletionDateTime()?->format(\DATE_RFC3339)
        );

        parent::__construct($message, $code, $previous);
    }
}
