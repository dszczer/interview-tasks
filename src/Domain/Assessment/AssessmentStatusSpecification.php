<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment;

use function in_array;

enum AssessmentStatusSpecification
{
    case VALID;
    case EXPIRED;
    case SUSPENDED;
    case WITHDRAWN;

    public static function isLocked(self $case): bool
    {
        return in_array($case->name, [self::SUSPENDED->name, self::WITHDRAWN->name], true);
    }
}
