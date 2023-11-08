<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Domain\Exception;

use Kodkod\InterviewTask\EvaluationProcess\Domain\User\RoleSpecification;
use Kodkod\InterviewTask\EvaluationProcess\Domain\User\UserInterface;
use Throwable;

class UserIsMissingRoleException extends \InvalidArgumentException
{
    public function __construct(
        UserInterface $user,
        RoleSpecification $roleSpecification,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $message = sprintf('User "%s" nas missing role "%s"', get_class($user), $roleSpecification->name);

        parent::__construct($message, $code, $previous);
    }
}
