<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Application\Repository;

use Kodkod\InterviewTask\EmployeeAllowance\Application\Service\InfrastructureDomainEntityMapper;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Entity\Employee;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Repository\EmployeeRepositoryInterface;
use Kodkod\InterviewTask\EmployeeAllowance\Infrastructure\Entity\Employee as DoctrineEmployee;
use Kodkod\InterviewTask\EmployeeAllowance\Infrastructure\Repository\EmployeeRepository as DoctrineEmployeeRepository;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    public function __construct(
        private readonly DoctrineEmployeeRepository $employeeRepository,
        private readonly InfrastructureDomainEntityMapper $domainEntityMapper
    ) {
    }

    public function save(Employee $employee): Employee
    {
        if (null !== $employee->getId()) {
            $entity = $this->employeeRepository->find($employee->getId());
        } else {
            $entity = $this->domainEntityMapper->mapEmployeeToInfrastructure($employee);
        }

        $this->employeeRepository->save($entity);

        return new Employee($entity->getId());
    }

    public function getById(int $id): ?Employee
    {
        /** @var DoctrineEmployee|null $entity */
        $entity = $this->employeeRepository->find($id);
        if (null === $entity) {
            return null;
        }

        return new Employee($entity->getId());
    }
}
