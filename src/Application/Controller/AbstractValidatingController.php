<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Application\Controller;

use Phpro\ApiProblem\Exception\ApiProblemException;
use Phpro\ApiProblem\Http\BadRequestProblem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractValidatingController extends AbstractController
{
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    final protected function validateRequest(object $value): void
    {
        $constraintViolationList = $this->validator->validate($value);
        if (0 < $constraintViolationList->count()) {
            throw new ApiProblemException(
                new BadRequestProblem($this->constraintValidationListToDetail($constraintViolationList))
            );
        }
    }

    private function constraintValidationListToDetail(ConstraintViolationListInterface $constraintViolationList): string
    {
        $detail = '';
        foreach ($constraintViolationList as $violation) {
            $detail .= $violation->getMessage() . "\n";
        }

        return rtrim($detail, "\n");
    }
}
