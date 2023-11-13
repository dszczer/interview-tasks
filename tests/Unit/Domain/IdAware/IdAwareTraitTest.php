<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Test\Unit\Domain\IdAware;

use Kodkod\InterviewTask\EmployeeAllowance\Domain\Exception\NotNaturalIntegerValueException;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\IdAware\IdAwareTrait;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TypeError;

class IdAwareTraitTest extends TestCase
{
    #[Test]
    #[DataProvider('provideGetId')]
    public function getId(mixed $id, ?string $expectedExceptionClassName = null): void
    {
        if (null !== $expectedExceptionClassName) {
            self::expectException($expectedExceptionClassName);
        }

        $classWithTrait = $this->createClassExtendingTrait($id, false);

        self::assertSame($id, $classWithTrait->getId());
    }

    public static function provideGetId(): array
    {
        return [
            // set #0
            [null],
            // set #1
            [0],
            // set #1
            [-1],
            // set #2
            [-10],
            // set #3
            [1],
            // set #4
            [2],
            // set #5
            [46],
            // set #6
            ['', TypeError::class],
            // set #7
            [1.0, TypeError::class],
            // set #8
            [true, TypeError::class],
            // set #9
            [new \stdClass(), TypeError::class],
        ];
    }

    #[Test]
    #[DataProvider('provideSelfValidateIdAware')]
    public function selfValidateIdAware(?int $id, ?string $expectedExceptionClassName = null): void
    {
        if (null !== $expectedExceptionClassName) {
            // this is validation assertion
            self::expectException($expectedExceptionClassName);
        }

        $classWithTrait = $this->createClassExtendingTrait($id);

        // validation passed, just add here dummy assertion
        self::assertIsObject($classWithTrait);
    }

    public static function provideSelfValidateIdAware(): array
    {
        return [
            // set #0
            [null],
            // set #1
            [1],
            // set #2
            [2],
            // set #3
            [129],
            // set #4
            [0, NotNaturalIntegerValueException::class],
            // set #5
            [-1, NotNaturalIntegerValueException::class],
            // set #6
            [-19, NotNaturalIntegerValueException::class],
        ];
    }

    private function createClassExtendingTrait(mixed $id = null, bool $selfValidate = true): object
    {
        return new class($id, $selfValidate) {
            use IdAwareTrait;

            public function __construct(?int $id = null, bool $selfValidate = true)
            {
                $this->id = $id;

                if ($selfValidate) {
                    $this->selfValidateIdAware();
                }
            }
        };
    }
}
