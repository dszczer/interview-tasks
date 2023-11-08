<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Test\Unit\Domain\Assessment;

use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\AssessmentStatusSpecification;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AssessmentSpecificationTest extends TestCase
{
    #[Test]
    public function isLocked(): void
    {
        self::assertTrue(AssessmentStatusSpecification::isLocked(AssessmentStatusSpecification::SUSPENDED));
        self::assertTrue(AssessmentStatusSpecification::isLocked(AssessmentStatusSpecification::WITHDRAWN));

        self::assertFalse(AssessmentStatusSpecification::isLocked(AssessmentStatusSpecification::VALID));
        self::assertFalse(AssessmentStatusSpecification::isLocked(AssessmentStatusSpecification::EXPIRED));

        // this is here just to make sure to cover new status case each time it is added to the codebase
        self::assertCount(4, AssessmentStatusSpecification::cases());
    }
}
