<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Application\Validator\CountryCode;

use Kodkod\InterviewTask\EmployeeAllowance\Domain\Specification\CountryCodeSpecification;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

use function is_string;

class CountryCodeValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $countryCodeCase = CountryCodeSpecification::tryFrom($value);

        if (null === $countryCodeCase) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ caseName }}', $value)
                ->addViolation();
        }
    }
}
