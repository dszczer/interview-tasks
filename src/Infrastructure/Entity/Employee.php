<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Infrastructure\Entity;

use Doctrine\ORM\Mapping\Entity;
use Kodkod\InterviewTask\EmployeeAllowance\Infrastructure\Repository\EmployeeRepository;

#[Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    use IdFieldTrait;
}
