<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Application\Validator\CountryCode;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class CountryCode extends Constraint
{
    public function __construct(
        public string $message = 'Given value "{{ caseName }}" is not a valid country code.',
        mixed $options = null,
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct($options, $groups, $payload);
    }
}
