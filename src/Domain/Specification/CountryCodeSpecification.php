<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Domain\Specification;

use function array_map;

enum CountryCodeSpecification: string
{
    case PL = 'PL';
    case DE = 'DE';
    case GB = 'GB';
}
