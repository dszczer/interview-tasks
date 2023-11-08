<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Domain;

use Carbon\CarbonImmutable;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\Assessment;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\AssessmentStatusSpecification;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\RatingSpecification;

final class AssessmentService
{
    private const EXPIRATION_DAYS = 365;

    /**
     * @return Assessment|null Null if assessment was not changed
     */
    public function suspend(Assessment $assessment, string $reason): ?Assessment
    {
        if (AssessmentStatusSpecification::WITHDRAWN->name === $assessment->getStatus()->name) {
            return null;
        }

        return $this->lock($assessment, $reason, AssessmentStatusSpecification::SUSPENDED);
    }

    /**
     * @return Assessment|null Null if assessment was not changed
     */
    public function withdraw(Assessment $assessment, string $reason): ?Assessment
    {
        return $this->lock($assessment, $reason, AssessmentStatusSpecification::WITHDRAWN);
    }

    /**
     * @return Assessment|null Null if assessment was not changed
     */
    public function unlock(Assessment $assessment): ?Assessment
    {
        if (AssessmentStatusSpecification::SUSPENDED->name !== $assessment->getStatus()->name) {
            return null;
        }

        return new Assessment(
            $assessment->getContract(),
            $assessment->getStandard(),
            $assessment->getEvaluations(),
            $assessment->getRating(),
            $assessment->getCompletionDateTime(),
            AssessmentStatusSpecification::VALID
        );
    }

    public function finish(Assessment $assessment, RatingSpecification $rating): Assessment
    {
        return new Assessment(
            $assessment->getContract(),
            $assessment->getStandard(),
            $assessment->getEvaluations(),
            $rating,
            CarbonImmutable::now()
        );
    }

    /**
     * @return Assessment|null Null if assessment was not changed
     */
    private function lock(Assessment $assessment, string $lockReason, AssessmentStatusSpecification $status): ?Assessment
    {
        if ($assessment->isExpired() || $this->hasExpired($assessment)) {
            return null;
        }

        $assessmentWithNewStatus = new Assessment(
            $assessment->getContract(),
            $assessment->getStandard(),
            $assessment->getEvaluations(),
            $assessment->getRating(),
            $assessment->getCompletionDateTime(),
            $status,
            $lockReason
        );

        if ($assessment->isLocked()) {
            if ($assessment->getStatus()->name !== $status->name) {
                return $assessmentWithNewStatus;
            }
        } else {
            return $assessmentWithNewStatus;
        }

        return null;
    }

    public function hasExpired(Assessment $assessment): bool
    {
        $carbonCompletionDateTime = new CarbonImmutable($assessment->getCompletionDateTime());
        $carbonExpiredDateTime = CarbonImmutable::now($carbonCompletionDateTime->getTimezone())
            ->subDays(self::EXPIRATION_DAYS);

        return $carbonExpiredDateTime->greaterThan($carbonCompletionDateTime);
    }
}
