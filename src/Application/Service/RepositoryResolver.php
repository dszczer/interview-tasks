<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Application\Service;

use Kodkod\InterviewTask\EmployeeAllowance\Domain\Repository\DelegationRepositoryInterface;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Repository\EmployeeRepositoryInterface;
use UnhandledMatchError;

readonly class RepositoryResolver
{
    public function __construct(
        private EmployeeRepositoryInterface $employeeRepository,
        private DelegationRepositoryInterface $delegationRepository
    ) {
    }

    /**
     * @param class-string $className
     */
    public function resolve(string $className): DelegationRepositoryInterface|EmployeeRepositoryInterface
    {
        return match ($className) {
            EmployeeRepositoryInterface::class => $this->employeeRepository,
            DelegationRepositoryInterface::class => $this->delegationRepository,
            default => throw new UnhandledMatchError(sprintf('Class "%s" is not supported as domain repository', $className)),
        };
    }
}
