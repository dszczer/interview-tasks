<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Infrastructure\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Kodkod\InterviewTask\EmployeeAllowance\Infrastructure\Repository\DelegationRepository;

#[Entity(repositoryClass: DelegationRepository::class)]
class Delegation
{
    use IdFieldTrait;

    #[Column(type: 'datetime', nullable: false)]
    private DateTimeInterface $startDateTime;

    #[Column(type: 'datetime', nullable: false)]
    private DateTimeInterface $endDateTime;

    #[JoinColumn(name: 'employee_id', referencedColumnName: 'id')]
    #[ManyToOne(targetEntity: Employee::class)]
    private Employee $employee;

    #[Column(type: 'string', nullable: false)]
    private string $countryCode;

    public function getStartDateTime(): DateTimeInterface
    {
        return $this->startDateTime;
    }

    public function setStartDateTime(DateTimeInterface $startDateTime): self
    {
        $this->startDateTime = $startDateTime;

        return $this;
    }

    public function getEndDateTime(): DateTimeInterface
    {
        return $this->endDateTime;
    }

    public function setEndDateTime(DateTimeInterface $endDateTime): self
    {
        $this->endDateTime = $endDateTime;

        return $this;
    }

    public function getEmployee(): Employee
    {
        return $this->employee;
    }

    public function setEmployee(Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }
}
