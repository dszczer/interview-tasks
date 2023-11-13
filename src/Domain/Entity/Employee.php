<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Domain\Entity;

use Kodkod\InterviewTask\EmployeeAllowance\Domain\IdAware\IdAwareTrait;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\IdAware\IdAwareInterface;

/**
 * @readonly
 */
final class Employee implements IdAwareInterface
{
    use IdAwareTrait;

    public function __construct(?int $id)
    {
        $this->id = $id;

        $this->selfValidate();
    }

    private function selfValidate(): void
    {
        $this->selfValidateIdAware();
    }
}
