<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Application\Dto;

final readonly class CreateEmployeeResponseDto
{
    public function __construct(public int $id)
    {
    }
}
