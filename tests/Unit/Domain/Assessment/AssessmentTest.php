<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Test\Unit\Domain\Assessment;

use DateTimeInterface;
use Faker\Factory;
use Faker\Generator;
use InvalidArgumentException;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\Assessment;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\AssessmentStatusSpecification;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\Evaluation;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\RatingSpecification;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\StandardSpecification;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Contract\Contract;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Contract\ContractStatusSpecification;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Exception\PerformingAssessmentForExpiredContractException;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Exception\PerformingEvaluationOnCompletedAssessmentException;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Exception\PerformingEvaluationOnExpiredAssessmentException;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Exception\PerformingEvaluationOnLockedAssessmentException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AssessmentTest extends TestCase
{
    private readonly Generator $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create();
    }

    #[Test]
    public function hasEvaluations(): void
    {
        $evaluation = new Evaluation();
        $assessment = $this->createAssessment(evaluations: [$evaluation]);
        $result = $assessment->getEvaluations();

        self::assertIsArray($result);
        self::assertCount(1, $result);
        self::assertArrayHasKey(0, $result);
        self::assertSame($evaluation, $result[0]);
    }

    #[Test]
    public function hasIndicatedStandard(): void
    {
        $standard = StandardSpecification::A;
        $contractMock = $this->createMock(Contract::class);
        $contractMock->method('getAssessmentStandards')->willReturn([$standard]);
        $assessment = $this->createAssessment($contractMock, $standard);

        self::assertSame($standard, $assessment->getStandard());
    }

    #[Test]
    public function invalidInstanceOfEvaluation(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessageMatches('/in \$evaluations is not an instanceof/');

        new Assessment(
            $this->createMock(Contract::class),
            StandardSpecification::A,
            evaluations: [new \stdClass()]
        );
    }

    #[Test]
    public function emptyLockReason(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessageMatches('/^Lock description must be set along with/');

        new Assessment(
            $this->createMock(Contract::class),
            StandardSpecification::A,
            [],
            statusSpecification: AssessmentStatusSpecification::SUSPENDED,
            lockReason: null
        );
    }

    #[Test]
    public function performEvaluationForValidContract(): void
    {
        $contractMock = $this->createMock(Contract::class);
        $contractMock->method('getStatus')->willReturn(ContractStatusSpecification::ACTIVE);
        $assessment = $this->createAssessment(contract: $contractMock);

        $result = $assessment->addEvaluation($this->createMock(Evaluation::class));

        self::assertCount(1, $result->getEvaluations());
    }

    #[Test]
    public function performEvaluationForExpiredContract(): void
    {
        $contractMock = $this->createMock(Contract::class);
        $contractMock->method('getStatus')->willReturn(ContractStatusSpecification::EXPIRED);
        $assessment = $this->createAssessment(contract: $contractMock, status: AssessmentStatusSpecification::EXPIRED);

        self::expectException(PerformingAssessmentForExpiredContractException::class);

        $assessment->addEvaluation($this->createMock(Evaluation::class));
    }

    #[Test]
    public function performEvaluationForSuspendedStatus(): void
    {
        $contractMock = $this->createMock(Contract::class);
        $contractMock->method('getStatus')->willReturn(ContractStatusSpecification::ACTIVE);
        $assessment = $this->createAssessment(
            contract: $contractMock,
            status: AssessmentStatusSpecification::SUSPENDED,
            lockReason: $this->faker->sentence()
        );

        self::expectException(PerformingEvaluationOnLockedAssessmentException::class);

        $assessment->addEvaluation($this->createMock(Evaluation::class));
    }

    #[Test]
    public function performEvaluationForWithdrawnStatus(): void
    {
        $contractMock = $this->createMock(Contract::class);
        $contractMock->method('getStatus')->willReturn(ContractStatusSpecification::ACTIVE);
        $assessment = $this->createAssessment(
            contract: $contractMock,
            status: AssessmentStatusSpecification::WITHDRAWN,
            lockReason: $this->faker->sentence()
        );

        self::expectException(PerformingEvaluationOnLockedAssessmentException::class);

        $assessment->addEvaluation($this->createMock(Evaluation::class));
    }

    #[Test]
    public function performEvaluationForExpiredStatus(): void
    {
        $contractMock = $this->createMock(Contract::class);
        $contractMock->method('getStatus')->willReturn(ContractStatusSpecification::ACTIVE);
        $assessment = $this->createAssessment(
            contract: $contractMock,
            completionDateTime: $this->faker->dateTime("2 years ago"),
            status: AssessmentStatusSpecification::EXPIRED
        );

        self::expectException(PerformingEvaluationOnExpiredAssessmentException::class);

        $assessment->addEvaluation($this->createMock(Evaluation::class));
    }

    #[Test]
    public function performEvaluationForCompletedAssessment(): void
    {
        $contractMock = $this->createMock(Contract::class);
        $contractMock->method('getStatus')->willReturn(ContractStatusSpecification::ACTIVE);
        $assessment = $this->createAssessment(
            contract: $contractMock,
            completionDateTime: $this->faker->dateTime("4 months ago")
        );

        self::expectException(PerformingEvaluationOnCompletedAssessmentException::class);

        $assessment->addEvaluation($this->createMock(Evaluation::class));
    }

    private function createAssessment(
        ?Contract $contract = null,
        StandardSpecification $specification = StandardSpecification::A,
        ?RatingSpecification $rating = null,
        array $evaluations = [],
        ?DateTimeInterface $completionDateTime = null,
        AssessmentStatusSpecification $status = AssessmentStatusSpecification::VALID,
        ?string $lockReason = null
    ): Assessment {
        return new Assessment(
            $contract ?? $this->createMock(Contract::class),
            $specification,
            $evaluations,
            $rating,
            $completionDateTime,
            $status,
            $lockReason
        );
    }
}
