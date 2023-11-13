<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Application\Validator\Entity;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class Entity extends Constraint
{
    public function __construct(
        public string $repositoryClassName,
        public string $message = 'Entity with id "{{ id }}" does not exists.',
        mixed $options = null,
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct($options, $groups, $payload);
    }
}
