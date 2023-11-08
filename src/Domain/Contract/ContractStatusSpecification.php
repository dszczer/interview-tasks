<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Domain\Contract;

enum ContractStatusSpecification
{
    case ACTIVE;
    case EXPIRED;
}
