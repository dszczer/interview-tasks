<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment;

use DateTimeInterface;
use InvalidArgumentException;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Contract\Contract;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Contract\ContractStatusSpecification;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Exception\PerformingAssessmentForExpiredContractException;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Exception\PerformingEvaluationOnCompletedAssessmentException;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Exception\PerformingEvaluationOnExpiredAssessmentException;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Exception\PerformingEvaluationOnLockedAssessmentException;

use function get_class;
use function sprintf;

class Assessment
{
    /**
     * @param Evaluation[] $evaluations
     */
    public function __construct(
        private readonly Contract $contract,
        private readonly StandardSpecification $standardSpecification,
        private readonly array $evaluations,
        private readonly ?RatingSpecification $ratingSpecification = null,
        private readonly ?DateTimeInterface $completionDateTime = null,
        private readonly AssessmentStatusSpecification $statusSpecification = AssessmentStatusSpecification::VALID,
        private readonly ?string $lockReason = null
    ) {
        $this->selfValidate();
    }

    public function isExpired(): bool
    {
        if (null === $this->completionDateTime) {
            return false;
        }

        return AssessmentStatusSpecification::EXPIRED->name === $this->statusSpecification->name;
    }

    public function isLocked(): bool
    {
        return AssessmentStatusSpecification::isLocked($this->statusSpecification);
    }

    public function getContract(): Contract
    {
        return $this->contract;
    }

    public function getLockReason(): ?string
    {
        return $this->lockReason;
    }

    /**
     * @return Evaluation[]
     */
    public function getEvaluations(): array
    {
        return $this->evaluations;
    }

    public function getRating(): ?RatingSpecification
    {
        return $this->ratingSpecification;
    }

    public function getStandard(): StandardSpecification
    {
        return $this->standardSpecification;
    }

    public function getStatus(): AssessmentStatusSpecification
    {
        return $this->statusSpecification;
    }

    public function getCompletionDateTime(): ?DateTimeInterface
    {
        return $this->completionDateTime;
    }

    /**
     * @throws PerformingEvaluationOnLockedAssessmentException
     * @throws PerformingEvaluationOnExpiredAssessmentException
     * @throws PerformingAssessmentForExpiredContractException
     * @throws PerformingEvaluationOnCompletedAssessmentException
     */
    public function addEvaluation(Evaluation $evaluation): self
    {
        if ($this->isLocked()) {
            throw new PerformingEvaluationOnLockedAssessmentException($this);
        }
        if ($this->isExpired()) {
            throw new PerformingEvaluationOnExpiredAssessmentException();
        }
        if (ContractStatusSpecification::EXPIRED->name === $this->contract->getStatus()->name) {
            throw new PerformingAssessmentForExpiredContractException($this->contract);
        }
        if (null !== $this->completionDateTime) {
            throw new PerformingEvaluationOnCompletedAssessmentException($this);
        }

        return new self(
            $this->contract,
            $this->standardSpecification,
            array_merge($this->evaluations, [$evaluation]),
            $this->ratingSpecification,
            $this->completionDateTime,
            $this->statusSpecification,
            $this->lockReason
        );
    }

    private function selfValidate(): void
    {
        foreach ($this->evaluations as $evaluation) {
            if (!$evaluation instanceof Evaluation) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Class "%s" in $evaluations is not an instanceof "%s"',
                        get_class($evaluation),
                        Evaluation::class
                    )
                );
            }
        }

        if ((null === $this->lockReason) && $this->isLocked()) {
            throw new InvalidArgumentException(
                sprintf(
                    'Lock description must be set along with "%s" locking status.',
                    $this->statusSpecification->name
                )
            );
        }
    }
}
