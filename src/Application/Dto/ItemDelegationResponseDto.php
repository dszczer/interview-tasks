<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Application\Dto;

final readonly class ItemDelegationResponseDto
{
    public function __construct(
        public string $start,
        public string $end,
        public string $country,
        public int $amountDue,
        public string $currency
    ) {
    }
}
