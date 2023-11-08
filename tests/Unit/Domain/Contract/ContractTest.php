<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Test\Unit\Domain\Contract;

use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\StandardSpecification;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Contract\Contract;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Contract\ContractStatusSpecification;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Exception\MissingMutualAssessmentStandardException;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Exception\UserIsMissingRoleException;
use Kodkod\InterviewTask\EvaluationProcess\Domain\User\RoleSpecification;
use Kodkod\InterviewTask\EvaluationProcess\Domain\User\UserInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ContractTest extends TestCase
{
    #[Test]
    public function supervisorAndUserHasMutualStandards(): void
    {
        $supervisorMock = $this->createMock(UserInterface::class);
        $supervisorMock
            ->method('getAssessmentStandards')
            ->willReturn([StandardSpecification::A, StandardSpecification::B]);
        $supervisorMock
            ->expects(self::once())
            ->method('getRoleSpecifications')
            ->willReturn([RoleSpecification::SUPERVISOR]);
        $customerMock = $this->createMock(UserInterface::class);
        $customerMock
            ->method('getAssessmentStandards')
            ->willReturn([StandardSpecification::A]);
        $customerMock
            ->expects(self::once())
            ->method('getRoleSpecifications')
            ->willReturn([RoleSpecification::CUSTOMER]);

        $contract = new Contract(ContractStatusSpecification::ACTIVE, $supervisorMock, $customerMock);
        $result = $contract->getAssessmentStandards();

        self::assertIsArray($result);
        self::assertCount(1, $result);
        self::assertSame([StandardSpecification::A], $result);
    }

    #[Test]
    public function noMutualStandardsFound(): void
    {
        $supervisorMock = $this->createMock(UserInterface::class);
        $supervisorMock
            ->method('getAssessmentStandards')
            ->willReturn([StandardSpecification::A]);
        $supervisorMock
            ->expects(self::once())
            ->method('getRoleSpecifications')
            ->willReturn([RoleSpecification::SUPERVISOR]);
        $customerMock = $this->createMock(UserInterface::class);
        $customerMock
            ->method('getAssessmentStandards')
            ->willReturn([StandardSpecification::B]);
        $customerMock
            ->expects(self::once())
            ->method('getRoleSpecifications')
            ->willReturn([RoleSpecification::CUSTOMER]);

        self::expectException(MissingMutualAssessmentStandardException::class);

        new Contract(ContractStatusSpecification::ACTIVE, $supervisorMock, $customerMock);
    }

    #[Test]
    public function supervisorHasMissingRole(): void
    {
        $supervisorMock = $this->createMock(UserInterface::class);
        $supervisorMock
            ->method('getAssessmentStandards')
            ->willReturn([StandardSpecification::A]);
        $customerMock = $this->createMock(UserInterface::class);
        $customerMock
            ->method('getAssessmentStandards')
            ->willReturn([StandardSpecification::B]);
        $customerMock
            ->method('getRoleSpecifications')
            ->willReturn([RoleSpecification::CUSTOMER]);

        self::expectException(UserIsMissingRoleException::class);

        new Contract(ContractStatusSpecification::ACTIVE, $supervisorMock, $customerMock);
    }

    #[Test]
    public function customerHasMissingRole(): void
    {
        $supervisorMock = $this->createMock(UserInterface::class);
        $supervisorMock
            ->method('getAssessmentStandards')
            ->willReturn([StandardSpecification::A]);
        $supervisorMock
            ->method('getRoleSpecifications')
            ->willReturn([RoleSpecification::SUPERVISOR]);
        $customerMock = $this->createMock(UserInterface::class);
        $customerMock
            ->method('getAssessmentStandards')
            ->willReturn([StandardSpecification::B]);

        self::expectException(UserIsMissingRoleException::class);

        new Contract(ContractStatusSpecification::ACTIVE, $supervisorMock, $customerMock);
    }

    #[Test]
    public function getStatus(): void
    {
        $supervisorMock = $this->createMock(UserInterface::class);
        $supervisorMock
            ->method('getAssessmentStandards')
            ->willReturn([StandardSpecification::A, StandardSpecification::B]);
        $supervisorMock
            ->expects(self::once())
            ->method('getRoleSpecifications')
            ->willReturn([RoleSpecification::SUPERVISOR]);
        $customerMock = $this->createMock(UserInterface::class);
        $customerMock
            ->method('getAssessmentStandards')
            ->willReturn([StandardSpecification::A]);
        $customerMock
            ->expects(self::once())
            ->method('getRoleSpecifications')
            ->willReturn([RoleSpecification::CUSTOMER]);

        $contract = new Contract(ContractStatusSpecification::ACTIVE, $supervisorMock, $customerMock);
        $status = $contract->getStatus();

        self::assertInstanceOf(ContractStatusSpecification::class, $status);
        self::assertSame(ContractStatusSpecification::ACTIVE->name, $status->name);
    }

    #[Test]
    public function getSupervisor(): void
    {
        $supervisorMock = $this->createMock(UserInterface::class);
        $supervisorMock
            ->method('getAssessmentStandards')
            ->willReturn([StandardSpecification::A, StandardSpecification::B]);
        $supervisorMock
            ->expects(self::once())
            ->method('getRoleSpecifications')
            ->willReturn([RoleSpecification::SUPERVISOR]);
        $customerMock = $this->createMock(UserInterface::class);
        $customerMock
            ->method('getAssessmentStandards')
            ->willReturn([StandardSpecification::A]);
        $customerMock
            ->expects(self::once())
            ->method('getRoleSpecifications')
            ->willReturn([RoleSpecification::CUSTOMER]);

        $contract = new Contract(ContractStatusSpecification::ACTIVE, $supervisorMock, $customerMock);
        $supervisor = $contract->getSupervisor();

        self::assertSame($supervisorMock, $supervisor);
    }

    #[Test]
    public function getCustomer(): void
    {
        $supervisorMock = $this->createMock(UserInterface::class);
        $supervisorMock
            ->method('getAssessmentStandards')
            ->willReturn([StandardSpecification::A, StandardSpecification::B]);
        $supervisorMock
            ->expects(self::once())
            ->method('getRoleSpecifications')
            ->willReturn([RoleSpecification::SUPERVISOR]);
        $customerMock = $this->createMock(UserInterface::class);
        $customerMock
            ->method('getAssessmentStandards')
            ->willReturn([StandardSpecification::A]);
        $customerMock
            ->expects(self::once())
            ->method('getRoleSpecifications')
            ->willReturn([RoleSpecification::CUSTOMER]);

        $contract = new Contract(ContractStatusSpecification::ACTIVE, $supervisorMock, $customerMock);
        $customer = $contract->getCustomer();

        self::assertSame($customerMock, $customer);
    }
}
