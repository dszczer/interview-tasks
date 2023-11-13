<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Domain\Repository;

use Kodkod\InterviewTask\EmployeeAllowance\Domain\Entity\Employee;

interface EmployeeRepositoryInterface extends GetByIdRepositoryInterface
{
    /**
     * If Employee has non-null ID, then UPDATE it.
     * If Employee has null ID, then CREATE it.
     *
     * @return Employee Entity with set ID property
     */
    public function save(Employee $employee): Employee;

    public function getById(int $id): ?Employee;
}
