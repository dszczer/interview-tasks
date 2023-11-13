<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Domain\Entity;

use Carbon\CarbonImmutable;
use DateTimeInterface;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Exception\DateTimeOutOfBoundsException;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\IdAware\IdAwareTrait;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\IdAware\IdAwareInterface;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Specification\CountryCodeSpecification;

/**
 * @readonly
 */
final class Delegation implements IdAwareInterface
{
    use IdAwareTrait;

    public function __construct(
        ?int $id,
        private readonly DateTimeInterface $startDateTime,
        private readonly DateTimeInterface $endDateTime,
        private readonly Employee $employee,
        private readonly CountryCodeSpecification $countryCode
    ) {
        $this->id = $id;

        $this->selfValidate();
    }

    public function getStartDateTime(): DateTimeInterface
    {
        return $this->startDateTime;
    }

    public function getEndDateTime(): DateTimeInterface
    {
        return $this->endDateTime;
    }

    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    public function getCountryCode(): CountryCodeSpecification
    {
        return $this->countryCode;
    }

    private function selfValidate(): void
    {
        $this->selfValidateIdAware();

        $carbonStartDateTime = CarbonImmutable::create($this->startDateTime);
        $carbonEndDateTime = CarbonImmutable::create($this->endDateTime);

        if ($carbonStartDateTime->greaterThan($carbonEndDateTime)) {
            throw new DateTimeOutOfBoundsException($this->startDateTime, $this->endDateTime);
        }
    }
}
