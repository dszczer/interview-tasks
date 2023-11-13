<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Domain\Repository;

use Kodkod\InterviewTask\EmployeeAllowance\Domain\IdAware\IdAwareInterface;

interface GetByIdRepositoryInterface
{
    public function getById(int $id): ?IdAwareInterface;
}
