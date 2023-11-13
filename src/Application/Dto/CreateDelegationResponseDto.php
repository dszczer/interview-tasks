<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Application\Dto;

final readonly class CreateDelegationResponseDto
{
    public function __construct(public int $id)
    {
    }
}
