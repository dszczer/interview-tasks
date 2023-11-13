<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Application\Validator\Entity;

use Kodkod\InterviewTask\EmployeeAllowance\Application\Service\RepositoryResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class EntityValidator extends ConstraintValidator
{
    public function __construct(private readonly RepositoryResolver $repositoryResolver)
    {
    }

    /**
     * @param Entity $constraint
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!is_numeric($value)) {
            throw new UnexpectedValueException($value, 'integer');
        }
        $repository = $this->repositoryResolver->resolve($constraint->repositoryClassName);

        if (null === $repository->getById($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ id }}', $value)
                ->addViolation();
        }
    }
}
