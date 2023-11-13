<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Application\Dto;

use Kodkod\InterviewTask\EmployeeAllowance\Application\Validator\Entity\Entity;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Repository\EmployeeRepositoryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class ListDelegationRequestDto
{
    public function __construct(
        #[Assert\NotBlank(allowNull: false)]
        #[Assert\Positive]
        #[Entity(EmployeeRepositoryInterface::class)]
        public int $employeeId
    ) {
    }
}
