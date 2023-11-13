<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Domain\IdAware;

interface IdAwareInterface
{
    public function getId(): ?int;
}
