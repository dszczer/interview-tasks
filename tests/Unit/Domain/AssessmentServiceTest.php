<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Test\Unit\Domain;

use Carbon\Carbon;
use DateTimeInterface;
use Faker\Factory;
use Faker\Generator;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\Assessment;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\AssessmentStatusSpecification;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\RatingSpecification;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\StandardSpecification;
use Kodkod\InterviewTask\EvaluationProcess\Domain\AssessmentService;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Contract\Contract;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AssessmentServiceTest extends TestCase
{
    private readonly AssessmentService $service;
    private readonly Generator $faker;

    protected function setUp(): void
    {
        $this->service = new AssessmentService();
        $this->faker = Factory::create();
    }

    #[Test]
    public function suspendValidAssessment(): void
    {
        $assessment = $this->createAssessment();
        $lockReason = $this->faker->sentence();
        $result = $this->service->suspend($assessment, $lockReason);

        self::assertNotNull($result);
        self::assertTrue($result->isLocked());
        self::assertSame(AssessmentStatusSpecification::SUSPENDED->name, $result->getStatus()->name);
        self::assertSame($lockReason, $result->getLockReason());
    }

    #[Test]
    public function withdrawValidAssessment(): void
    {
        $assessment = $this->createAssessment();
        $lockReason = $this->faker->sentence();
        $result = $this->service->withdraw($assessment, $lockReason);

        self::assertNotNull($result);
        self::assertTrue($result->isLocked());
        self::assertSame(AssessmentStatusSpecification::WITHDRAWN->name, $result->getStatus()->name);
        self::assertSame($lockReason, $result->getLockReason());
    }

    #[Test]
    public function suspendSuspendedAssessment(): void
    {
        $lockReason = $this->faker->sentence();
        $assessment = $this->createAssessment(status: AssessmentStatusSpecification::SUSPENDED, lockReason: $lockReason);
        $result = $this->service->suspend($assessment, $this->faker->sentence());

        // no change in state should occur
        self::assertNull($result);
        self::assertSame(AssessmentStatusSpecification::SUSPENDED->name, $assessment->getStatus()->name);
        self::assertSame($lockReason, $assessment->getLockReason());
    }

    #[Test]
    public function suspendWithdrawnAssessment(): void
    {
        $lockReason = $this->faker->sentence();
        $assessment = $this->createAssessment(status: AssessmentStatusSpecification::WITHDRAWN, lockReason: $lockReason);
        $result = $this->service->suspend($assessment, $this->faker->sentence());

        // no change in state should occur
        self::assertNull($result);
        self::assertSame(AssessmentStatusSpecification::WITHDRAWN->name, $assessment->getStatus()->name);
        self::assertSame($lockReason, $assessment->getLockReason());
    }

    #[Test]
    public function withdrawWithdrawnAssessment(): void
    {
        $lockReason = $this->faker->sentence();
        $assessment = $this->createAssessment(status: AssessmentStatusSpecification::WITHDRAWN, lockReason: $lockReason);
        $result = $this->service->withdraw($assessment, $this->faker->sentence());

        // no change in state should occur
        self::assertNull($result);
        self::assertSame(AssessmentStatusSpecification::WITHDRAWN->name, $assessment->getStatus()->name);
        self::assertSame($lockReason, $assessment->getLockReason());
    }

    #[Test]
    public function suspendExpiredAssessment(): void
    {
        $assessment = $this->createAssessment(
            completionDateTime: Carbon::now()->subYears(2),
            status: AssessmentStatusSpecification::EXPIRED
        );
        $result = $this->service->suspend($assessment, $this->faker->sentence());

        // no change in state should occur
        self::assertNull($result);
        self::assertSame(AssessmentStatusSpecification::EXPIRED->name, $assessment->getStatus()->name);
        self::assertNull($assessment->getLockReason());
    }

    #[Test]
    public function withdrawExpiredAssessment(): void
    {
        $assessment = $this->createAssessment(
            completionDateTime: Carbon::now()->subYears(2),
            status: AssessmentStatusSpecification::EXPIRED
        );
        $result = $this->service->withdraw($assessment, $this->faker->sentence());

        // no change in state should occur
        self::assertNull($result);
        self::assertSame(AssessmentStatusSpecification::EXPIRED->name, $assessment->getStatus()->name);
        self::assertNull($assessment->getLockReason());
    }

    #[Test]
    public function unlockValidAssessment(): void
    {
        $assessment = $this->createAssessment();
        $result = $this->service->unlock($assessment);

        // no change in state should occur
        self::assertNull($result);
        self::assertSame(AssessmentStatusSpecification::VALID->name, $assessment->getStatus()->name);
        self::assertNull($assessment->getLockReason());
    }

    #[Test]
    public function unlockSuspendedAssessment(): void
    {
        $assessment = $this->createAssessment(
            status: AssessmentStatusSpecification::SUSPENDED,
            lockReason: $this->faker->sentence()
        );
        $result = $this->service->unlock($assessment);

        self::assertNotNull($result);
        self::assertSame(AssessmentStatusSpecification::VALID->name, $result->getStatus()->name);
        self::assertNull($result->getLockReason());
    }

    #[Test]
    public function unlockWithdrawnAssessment(): void
    {
        $lockReason = $this->faker->sentence();
        $assessment = $this->createAssessment(status: AssessmentStatusSpecification::WITHDRAWN, lockReason: $lockReason);
        $result = $this->service->unlock($assessment);

        self::assertNull($result);
        self::assertSame(AssessmentStatusSpecification::WITHDRAWN->name, $assessment->getStatus()->name);
        self::assertSame($lockReason, $assessment->getLockReason());
    }

    #[Test]
    public function unlockExpiredAssessment(): void
    {
        $assessment = $this->createAssessment(
            completionDateTime: Carbon::now()->subYears(2),
            status: AssessmentStatusSpecification::EXPIRED
        );
        $result = $this->service->unlock($assessment);

        self::assertNull($result);
        self::assertSame(AssessmentStatusSpecification::EXPIRED->name, $assessment->getStatus()->name);
        self::assertNull($assessment->getLockReason());
    }

    #[Test]
    public function finishWithPositiveRating(): void
    {
        $assessment = $this->createAssessment();
        $result = $this->service->finish($assessment, RatingSpecification::POSITIVE);

        self::assertNotNull($result);
        self::assertFalse($result->isLocked());
        self::assertFalse($result->isExpired());
        self::assertSame(AssessmentStatusSpecification::VALID->name, $result->getStatus()->name);
        self::assertNull($result->getLockReason());
        self::assertNotNull($result->getRating());
        self::assertSame(RatingSpecification::POSITIVE->name, $result->getRating()->name);
    }

    #[Test]
    public function finishWithNegativeRating(): void
    {
        $assessment = $this->createAssessment();
        $result = $this->service->finish($assessment, RatingSpecification::NEGATIVE);

        self::assertNotNull($result);
        self::assertFalse($result->isLocked());
        self::assertFalse($result->isExpired());
        self::assertSame(AssessmentStatusSpecification::VALID->name, $result->getStatus()->name);
        self::assertNull($result->getLockReason());
        self::assertNotNull($result->getRating());
        self::assertSame(RatingSpecification::NEGATIVE->name, $result->getRating()->name);
    }

    private function createAssessment(
        ?Contract $contract = null,
        ?DateTimeInterface $completionDateTime = null,
        AssessmentStatusSpecification $status = AssessmentStatusSpecification::VALID,
        ?string $lockReason = null
    ): Assessment {
        return new Assessment(
            $contract ?? $this->createMock(Contract::class),
                StandardSpecification::A,
            [],
            null,
            $completionDateTime,
            $status,
            $lockReason
        );
    }
}
