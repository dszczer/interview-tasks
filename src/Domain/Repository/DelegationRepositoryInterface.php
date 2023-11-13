<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Domain\Repository;

use Kodkod\InterviewTask\EmployeeAllowance\Domain\Entity\Delegation;

interface DelegationRepositoryInterface extends GetByIdRepositoryInterface
{
    /**
     * If Delegation has non-null ID, then UPDATE it.
     * If Delegation has null ID, then CREATE it.
     *
     * @return Delegation Entity with set ID property
     */
    public function save(Delegation $delegation): Delegation;

    /**
     * @return array<Delegation>
     */
    public function getAllByEmployeeId(int $id): array;

    public function getById(int $id): ?Delegation;
}
