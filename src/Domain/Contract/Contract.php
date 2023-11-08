<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Domain\Contract;

use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\StandardSpecification;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Exception\MissingMutualAssessmentStandardException;
use Kodkod\InterviewTask\EvaluationProcess\Domain\Exception\UserIsMissingRoleException;
use Kodkod\InterviewTask\EvaluationProcess\Domain\User\RoleAwareInterface;
use Kodkod\InterviewTask\EvaluationProcess\Domain\User\RoleSpecification;
use Kodkod\InterviewTask\EvaluationProcess\Domain\User\UserInterface;

class Contract
{
    /**
     * @var StandardSpecification[]
     */
    private readonly array $assessmentStandards;

    /**
     * @throws UserIsMissingRoleException
     * @throws MissingMutualAssessmentStandardException
     */
    public function __construct(
        private readonly ContractStatusSpecification $statusSpecification,
        private readonly UserInterface $supervisor,
        private readonly UserInterface $customer
    ) {
        $this->assessmentStandards = $this->getMutualAssessmentStandards();
        $this->selfValidate();
    }

    public function getStatus(): ContractStatusSpecification
    {
        return $this->statusSpecification;
    }

    public function getSupervisor(): UserInterface
    {
        return $this->supervisor;
    }

    public function getCustomer(): UserInterface
    {
        return $this->customer;
    }

    /**
     * @return StandardSpecification[]
     */
    public function getAssessmentStandards(): array
    {
        return $this->assessmentStandards;
    }

    /**
     * @return StandardSpecification[]
     */
    private function getMutualAssessmentStandards(): array
    {
        /** @var StandardSpecification[] $mutualAssessmentStandards */
        $mutualAssessmentStandards = [];
        foreach ($this->supervisor->getAssessmentStandards() as $supervisorStandard) {
            foreach ($this->customer->getAssessmentStandards() as $customerStandard) {
                if ($supervisorStandard->name === $customerStandard->name) {
                    $mutualAssessmentStandards[] = $customerStandard;
                }
            }
        }

        return $mutualAssessmentStandards;
    }

    /**
     * @throws UserIsMissingRoleException
     * @throws MissingMutualAssessmentStandardException
     */
    private function selfValidate(): void
    {
        if (!$this->hasRole(RoleSpecification::SUPERVISOR, $this->supervisor)) {
            throw new UserIsMissingRoleException($this->supervisor, RoleSpecification::SUPERVISOR);
        }
        if (!$this->hasRole(RoleSpecification::CUSTOMER, $this->customer)) {
            throw new UserIsMissingRoleException($this->customer, RoleSpecification::CUSTOMER);
        }
        if (0 === count($this->assessmentStandards)) {
            throw new MissingMutualAssessmentStandardException($this->supervisor, $this->customer);
        }
    }

    private function hasRole(RoleSpecification $roleSpecification, RoleAwareInterface $roleAware): bool
    {
        foreach ($roleAware->getRoleSpecifications() as $testedRole) {
            if ($roleSpecification->name === $testedRole->name) {
                return true;
            }
        }

        return false;
    }
}
