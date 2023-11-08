<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Test\Unit\Domain\User;

use Faker\Factory;
use Faker\Generator;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\StandardSpecification;
use Kodkod\InterviewTask\EvaluationProcess\Domain\User\AbstractUser;
use Kodkod\InterviewTask\EvaluationProcess\Domain\User\RoleSpecification;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AbstractUserTest extends TestCase
{
    private readonly Generator $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
    }

    #[Test]
    public function getName(): void
    {
        $name = $this->faker->name();
        $user = $this->createInstanceForAbstractUser(name: $name);
        $result = $user->getName();

        self::assertSame($name, $result);
    }

    #[Test]
    public function getRoleSpecifications(): void
    {
        $name = $this->faker->name();
        $user = $this->createInstanceForAbstractUser(name: $name, roles: [RoleSpecification::CUSTOMER]);
        $result = $user->getRoleSpecifications();

        self::assertIsArray($result);
        self::assertCount(1, $result);
        self::assertArrayHasKey(0, $result);
        self::assertSame(RoleSpecification::CUSTOMER->name, $result[0]->name);
    }

    #[Test]
    public function getAssessmentStandards(): void
    {
        $name = $this->faker->name();
        $user = $this->createInstanceForAbstractUser(name: $name, standards: [StandardSpecification::A]);
        $result = $user->getAssessmentStandards();

        self::assertIsArray($result);
        self::assertCount(1, $result);
        self::assertArrayHasKey(0, $result);
        self::assertSame(StandardSpecification::A->name, $result[0]->name);
    }

    private function createInstanceForAbstractUser(
        ?string $name = null,
        array $roles = [],
        array $standards = []
    ): AbstractUser {
        return new class(
            $name ?? $this->faker->name(),
            $roles,
            $standards
        ) extends AbstractUser {};
    }
}
