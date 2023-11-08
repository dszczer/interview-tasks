<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment;

interface RatingSpecificationAwareInterface
{
    /**
     * @return RatingSpecification[]
     */
    public function getAssessmentRatings(): array;
}
