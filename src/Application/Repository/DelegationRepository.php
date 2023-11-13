<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Application\Repository;

use Kodkod\InterviewTask\EmployeeAllowance\Application\Service\InfrastructureDomainEntityMapper;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Entity\Delegation;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Repository\DelegationRepositoryInterface;
use Kodkod\InterviewTask\EmployeeAllowance\Infrastructure\Entity\Delegation as DoctrineDelegation;
use Kodkod\InterviewTask\EmployeeAllowance\Infrastructure\Repository\DelegationRepository as DoctrineDelegationRepository;
use Kodkod\InterviewTask\EmployeeAllowance\Infrastructure\Repository\EmployeeRepository as DoctrineEmployeeRepository;

class DelegationRepository implements DelegationRepositoryInterface
{
    public function __construct(
        private readonly DoctrineDelegationRepository $delegationRepository,
        private readonly DoctrineEmployeeRepository $employeeRepository,
        private readonly InfrastructureDomainEntityMapper $domainEntityMapper
    ) {
    }

    public function save(Delegation $delegation): Delegation
    {
        if (null !== $delegation->getId()) {
            $entity = $this->delegationRepository->find($delegation->getId());
        } else {
            $entity = $this->domainEntityMapper->mapDelegationToDoctrine($delegation);
            $entity->setEmployee($this->employeeRepository->find($delegation->getEmployee()->getId()));
        }

        $this->delegationRepository->save($entity);

        return $this->domainEntityMapper->mapDelegationToDomain($entity);
    }

    public function getAllByEmployeeId(int $id): array
    {
        $collection = $this->delegationRepository->getByEmployeeId($id);

        $delegations = [];
        foreach ($collection as $delegation) {
            $delegations[] = $this->domainEntityMapper->mapDelegationToDomain($delegation);
        }

        return $delegations;
    }

    public function getById(int $id): ?Delegation
    {
        /** @var DoctrineDelegation|null $entity */
        $entity = $this->delegationRepository->find($id);
        if (null === $entity) {
            return null;
        }

        return $this->domainEntityMapper->mapDelegationToDomain($entity);
    }
}
