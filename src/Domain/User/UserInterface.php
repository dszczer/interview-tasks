<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Domain\User;

use Kodkod\InterviewTask\EvaluationProcess\Domain\Assessment\StandardSpecificationAwareInterface;

interface UserInterface extends RoleAwareInterface, StandardSpecificationAwareInterface
{
    public function getName(): string;
}
