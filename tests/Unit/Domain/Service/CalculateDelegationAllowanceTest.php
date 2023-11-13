<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Test\Unit\Domain\Service;

use Carbon\Carbon;
use DateTimeInterface;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Entity\Delegation;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Entity\Employee;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Service\CalculateDelegationAllowance;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Service\MatchCountryCodeWithAllowanceRate;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Specification\AllowanceRateSpecification;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Specification\CountryCodeSpecification;
use Kodkod\InterviewTask\EmployeeAllowance\Test\Unit\Domain\Entity\DelegationTest;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DependsOnClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CalculateDelegationAllowanceTest extends TestCase
{
    private const ALLOWANCE_BASIC_RATE = 10;
    private const ALLOWANCE_BONUS_RATE = 20;

    private readonly MatchCountryCodeWithAllowanceRate|MockObject $matchCountryCodeWithAllowanceRateMock;

    protected function setUp(): void
    {
        $this->matchCountryCodeWithAllowanceRateMock = $this->createMock(MatchCountryCodeWithAllowanceRate::class);
        $this->matchCountryCodeWithAllowanceRateMock->method('getAllowanceRateFor')
            ->willReturnCallback(function (CountryCodeSpecification $countryCode): AllowanceRateSpecification {
                return match ($countryCode->name) {
                    CountryCodeSpecification::PL->name => AllowanceRateSpecification::PL,
                    CountryCodeSpecification::DE->name => AllowanceRateSpecification::DE,
                    CountryCodeSpecification::GB->name => AllowanceRateSpecification::GB,
                };
            });
    }

    #[Test]
    #[DataProvider('provideForDelegation')]
    #[DependsOnClass(DelegationTest::class)]
    public function forDelegation(Delegation $givenDelegation, int $expected): void
    {
        $service = new CalculateDelegationAllowance($this->matchCountryCodeWithAllowanceRateMock);

        $result = $service->forDelegation($givenDelegation);

        self::assertSame($expected, $result);
    }

    public static function provideForDelegation(): array
    {
        return [
            // set #0
            [
                self::buildDelegation(Carbon::create(2023, 10, 26, 5), Carbon::create(2023, 10, 26, 6)),
                0,
            ],
            // set #1
            [
                self::buildDelegation(Carbon::create(2023, 10, 26, 8), Carbon::create(2023, 10, 26, 15)),
                0,
            ],
            // set #2
            [
                self::buildDelegation(Carbon::create(2023, 10, 26, 8), Carbon::create(2023, 10, 26, 16)),
                self::ALLOWANCE_BASIC_RATE,
            ],
            // set #3
            [
                self::buildDelegation(Carbon::create(2020, 4, 20, 8), Carbon::create(2020, 4, 21, 16)),
                2 * self::ALLOWANCE_BASIC_RATE,
            ],
            // set #4
            [
                self::buildDelegation(Carbon::create(2020, 4, 24, 8), Carbon::create(2020, 4, 28, 16)),
                3 * self::ALLOWANCE_BASIC_RATE,
            ],
            // set #5
            [
                self::buildDelegation(Carbon::create(2023, 11, 6, 8), Carbon::create(2023, 11, 13, 16)),
                5 * self::ALLOWANCE_BASIC_RATE + self::ALLOWANCE_BONUS_RATE,
            ],
            // set #6
            [
                self::buildDelegation(Carbon::create(2023,11,6,8), Carbon::create(2023, 11, 15, 16)),
                5 * self::ALLOWANCE_BASIC_RATE + 3 * self::ALLOWANCE_BONUS_RATE,
            ],
        ];
    }

    private static function buildDelegation(
        DateTimeInterface $startDateTime,
        DateTimeInterface $endDateTime
    ): Delegation {
        return new Delegation(null, $startDateTime, $endDateTime, new Employee(null), CountryCodeSpecification::PL);
    }
}
