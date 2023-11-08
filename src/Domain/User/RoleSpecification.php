<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EvaluationProcess\Domain\User;

enum RoleSpecification
{
    /*
     * User general roles.
     */
    case CUSTOMER;
    case SUPERVISOR;
}
