<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Domain\Exception;

use InvalidArgumentException;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Contract\Contract;
use Throwable;

class PerformingAssessmentForExpiredContractException extends InvalidArgumentException
{
    public function __construct(Contract $contract, int $code = 0, ?Throwable $previous = null)
    {
        $message = sprintf(
            'Contract with client "%s" supervised by "%s" is expired and evaluation cannot be performed.',
            $contract->getCustomer()->getName(),
            $contract->getSupervisor()->getName()
        );
        parent::__construct($message, $code, $previous);
    }
}
