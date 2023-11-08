<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Domain\Exception;

use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\StandardSpecificationAwareInterface;
use Throwable;

class MissingMutualAssessmentStandardException extends \InvalidArgumentException
{
    public function __construct(
        StandardSpecificationAwareInterface $specificationAwareA,
        StandardSpecificationAwareInterface $specificationAwareB,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $message = sprintf(
            'Object "%s" has no mutual Assessment Standards with object "%s".',
            get_class($specificationAwareA),
            get_class($specificationAwareB)
        );

        parent::__construct($message, $code, $previous);
    }
}
