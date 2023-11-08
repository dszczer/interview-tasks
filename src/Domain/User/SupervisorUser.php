<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Domain\User;

use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\StandardSpecification;

final class SupervisorUser extends AbstractUser
{
    /**
     * @param StandardSpecification[] $standardSpecifications List of Assessment Standards this User handles
     */
    public function __construct(string $name, protected array $standardSpecifications)
    {
        parent::__construct($name, [RoleSpecification::SUPERVISOR], $this->standardSpecifications);
    }
}
