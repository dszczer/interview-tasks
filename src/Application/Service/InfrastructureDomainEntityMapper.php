<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Application\Service;

use Kodkod\InterviewTask\EmployeeAllowance\Domain\Entity\Delegation as DomainDelegation;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Entity\Employee as DomainEmployee;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Specification\CountryCodeSpecification;
use Kodkod\InterviewTask\EmployeeAllowance\Infrastructure\Entity\Delegation as InfrastructureDelegation;
use Kodkod\InterviewTask\EmployeeAllowance\Infrastructure\Entity\Employee as InfrastructureEmployee;

class InfrastructureDomainEntityMapper
{
    public function mapEmployeeToDomain(InfrastructureEmployee $entity): DomainEmployee
    {
        return new DomainEmployee($entity->getId());
    }

    public function mapEmployeeToInfrastructure(DomainEmployee $employee): InfrastructureEmployee
    {
        $entity = new InfrastructureEmployee();

        $entity->setId($employee->getId());

        return $entity;
    }

    public function mapDelegationToDomain(InfrastructureDelegation $entity): DomainDelegation
    {
        return new DomainDelegation(
            $entity->getId(),
            $entity->getStartDateTime(),
            $entity->getEndDateTime(),
            $this->mapEmployeeToDomain($entity->getEmployee()),
            CountryCodeSpecification::from($entity->getCountryCode())
        );
    }

    public function mapDelegationToDoctrine(DomainDelegation $delegation): InfrastructureDelegation
    {
        $entity = new InfrastructureDelegation();

        return $entity->setId($delegation->getId())
            ->setStartDateTime($delegation->getStartDateTime())
            ->setEndDateTime($delegation->getEndDateTime())
            ->setEmployee($this->mapEmployeeToInfrastructure($delegation->getEmployee()))
            ->setCountryCode($delegation->getCountryCode()->value);
    }
}
