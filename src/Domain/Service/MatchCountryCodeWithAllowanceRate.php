<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Domain\Service;

use Kodkod\InterviewTask\EmployeeAllowance\Domain\Specification\AllowanceRateSpecification;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Specification\CountryCodeSpecification;
use UnhandledMatchError;

/**
 * @final
 *
 * @readonly
 */
class MatchCountryCodeWithAllowanceRate
{
    public function getAllowanceRateFor(CountryCodeSpecification $countryCode): AllowanceRateSpecification
    {
        return match ($countryCode->name) {
            CountryCodeSpecification::PL->name => AllowanceRateSpecification::PL,
            CountryCodeSpecification::DE->name => AllowanceRateSpecification::DE,
            CountryCodeSpecification::GB->name => AllowanceRateSpecification::GB,
            default => throw new UnhandledMatchError(sprintf('No match found for "%s" case.', $countryCode->name))
        };
    }
}
