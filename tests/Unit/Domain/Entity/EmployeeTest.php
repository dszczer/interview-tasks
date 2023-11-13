<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Test\Unit\Domain\Entity;

use Kodkod\InterviewTask\EmployeeAllowance\Domain\Entity\Employee;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Exception\NotNaturalIntegerValueException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class EmployeeTest extends TestCase
{
    #[Test]
    public function selfValidateConstructor(): void
    {
        new Employee(null);
        new Employee(1);
        new Employee(2);

        self::expectException(NotNaturalIntegerValueException::class);

        new Employee(0);
    }
}
