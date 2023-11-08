<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment;

interface StandardSpecificationAwareInterface
{
    /**
     * @return StandardSpecification[]
     */
    public function getAssessmentStandards(): array;
}
