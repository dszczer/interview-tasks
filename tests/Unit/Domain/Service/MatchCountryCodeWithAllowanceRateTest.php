<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Test\Unit\Domain\Service;

use Kodkod\InterviewTask\EmployeeAllowance\Domain\Service\MatchCountryCodeWithAllowanceRate;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Specification\AllowanceRateSpecification;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Specification\CountryCodeSpecification;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class MatchCountryCodeWithAllowanceRateTest extends TestCase
{
    #[Test]
    #[DataProvider('provideGetAllowanceFor')]
    public function getAllowanceFor(CountryCodeSpecification $given, AllowanceRateSpecification $expected): void
    {
        $service = new MatchCountryCodeWithAllowanceRate();

        $result = $service->getAllowanceRateFor($given);

        self::assertInstanceOf(AllowanceRateSpecification::class, $result);
        self::assertSame($expected->name, $result->name);
        self::assertSame($expected->value, $result->value);
    }

    public static function provideGetAllowanceFor(): array
    {
        return [
            // set #0
            [
                CountryCodeSpecification::PL,
                AllowanceRateSpecification::PL,
            ],
            // set #1
            [
                CountryCodeSpecification::DE,
                AllowanceRateSpecification::DE,
            ],
            // set #2
            [
                CountryCodeSpecification::GB,
                AllowanceRateSpecification::GB,
            ],
        ];
    }
}
