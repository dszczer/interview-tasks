<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Domain\Specification;

enum AllowanceRateSpecification: int
{
    case PL = 10;
    case DE = 50;
    case GB = 75;
}
