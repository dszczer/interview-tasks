<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Test\Unit\Domain\User;

use Faker\Factory;
use Faker\Generator;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\StandardSpecification;
use Kodkod\InterviewTask\EvaluationProcess\Domain\User\CustomerUser;
use Kodkod\InterviewTask\EvaluationProcess\Domain\User\RoleSpecification;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class CustomerUserTest extends TestCase
{
    private readonly Generator $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
    }

    #[Test]
    public function constructorPassValidParametersToTheParent(): void
    {
        $name = $this->faker->name();
        $customer = new CustomerUser($name, [StandardSpecification::A]);
        $roles = $customer->getRoleSpecifications();
        $standards = $customer->getAssessmentStandards();

        self::assertSame($customer->getName(), $name);

        self::assertCount(1, $roles);
        self::assertArrayHasKey(0, $roles);
        self::assertSame($roles[0]->name, RoleSpecification::CUSTOMER->name);

        self::assertCount(1, $standards);
        self::assertArrayHasKey(0, $standards);
        self::assertSame($standards[0]->name, StandardSpecification::A->name);
    }
}
