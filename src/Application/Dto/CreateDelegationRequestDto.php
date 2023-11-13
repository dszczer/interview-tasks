<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Application\Dto;

use Kodkod\InterviewTask\EmployeeAllowance\Application\Validator\CountryCode\CountryCode;
use Kodkod\InterviewTask\EmployeeAllowance\Application\Validator\Entity\Entity;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Repository\EmployeeRepositoryInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateDelegationRequestDto
{
    public function __construct(
        #[Assert\NotBlank(allowNull: false)]
        #[Assert\DateTime(format: 'Y-m-d H:i:s')]
        #[SerializedName('start')]
        public string $startDateTime,

        #[Assert\NotBlank(allowNull: false)]
        #[Assert\DateTime(format: 'Y-m-d H:i:s')]
        #[Assert\GreaterThanOrEqual(propertyPath: 'startDateTime')]
        #[SerializedName('end')]
        public string $endDateTime,

        #[Assert\NotBlank(allowNull: false)]
        #[Assert\Positive]
        #[Entity(EmployeeRepositoryInterface::class)]
        #[SerializedName('employee_id')]
        public int $employeeId,

        #[Assert\NotBlank(allowNull: false)]
        #[CountryCode]
        #[SerializedName('country')]
        public string $countryCodeSpecification
    ) {
    }
}
