<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Test\Unit\Domain\Entity;

use DateTime;
use DateTimeInterface;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Entity\Delegation;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Entity\Employee;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Exception\DateTimeOutOfBoundsException;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Specification\CountryCodeSpecification;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class DelegationTest extends TestCase
{
    #[Test]
    public function selfValidationConstructor(): void
    {
        $this->createDelegation(null, new DateTime(), new DateTime('tomorrow'));
        $this->createDelegation(1, new DateTime('2 days ago'), new DateTime());
        $this->createDelegation(2, new DateTime('1 day ago'), new DateTime('+1 hour'));

        self::expectException(DateTimeOutOfBoundsException::class);

        $this->createDelegation(1, new DateTime('+1 hour'), new DateTime());
    }

    #[Test]
    public function getStartDateTime(): void
    {
        $startDateTime = new DateTime('2020-01-01 00:00:00');
        $delegation = $this->createDelegation(null, $startDateTime, new DateTime());

        $result = $delegation->getStartDateTime();

        self::assertInstanceOf(\DateTimeInterface::class, $result);
        self::assertSame($startDateTime->format(DateTimeInterface::ATOM), $result->format(DateTimeInterface::ATOM));
    }

    #[Test]
    public function getEndDateTime(): void
    {
        $endDateTime = new DateTime('2020-01-01 00:00:00');
        $delegation = $this->createDelegation(null, new DateTime('2019-12-30 00:00:00'), $endDateTime);

        $result = $delegation->getEndDateTime();

        self::assertInstanceOf(\DateTimeInterface::class, $result);
        self::assertSame($endDateTime->format(DateTimeInterface::ATOM), $result->format(DateTimeInterface::ATOM));
    }

    #[Test]
    public function getEmployee(): void
    {
        $employee = new Employee(null);
        $delegation = $this->createDelegation(null, new DateTime(), new DateTime(), $employee);

        $result = $delegation->getEmployee();

        self::assertSame($employee, $result);
    }

    #[Test]
    public function getCountryCode(): void
    {
        $countryCode = CountryCodeSpecification::DE;
        $delegation = $this->createDelegation(null, new DateTime(), new DateTime(), new Employee(null), $countryCode);

        $result = $delegation->getCountryCode();

        self::assertInstanceOf(CountryCodeSpecification::class, $result);
        self::assertSame($countryCode->name, $result->name);
        self::assertSame($countryCode->value, $result->value);
    }

    private function createDelegation(
        ?int $id,
        DateTimeInterface $startDateTime,
        DateTimeInterface $endDateTime,
        ?Employee $employee = null,
        CountryCodeSpecification $countryCode = CountryCodeSpecification::PL
    ): Delegation {
        return new Delegation(
            $id,
            $startDateTime,
            $endDateTime,
            $employee ?? new Employee(null),
            $countryCode
        );
    }
}
