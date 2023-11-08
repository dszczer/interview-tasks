<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Domain\User;

use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\StandardSpecification;

abstract class AbstractUser implements UserInterface
{
    /**
     * @param RoleSpecification[]     $roleSpecifications     List of Roles this User has
     * @param StandardSpecification[] $standardSpecifications List of Assessment Standards this User handles
     */
    public function __construct(
        protected string $name,
        protected array $roleSpecifications,
        protected array $standardSpecifications
    ) {
    }

    final public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return RoleSpecification[]
     */
    final public function getRoleSpecifications(): array
    {
        return $this->roleSpecifications;
    }

    /**
     * @return StandardSpecification[]
     */
    final public function getAssessmentStandards(): array
    {
        return $this->standardSpecifications;
    }
}
