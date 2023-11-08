<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Domain\User;

interface RoleAwareInterface
{
    /**
     * @return RoleSpecification[]
     */
    public function getRoleSpecifications(): array;
}
