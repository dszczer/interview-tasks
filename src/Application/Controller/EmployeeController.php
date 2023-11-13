<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Application\Controller;

use Kodkod\InterviewTask\EmployeeAllowance\Application\Dto\CreateEmployeeResponseDto;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Entity\Employee;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Repository\EmployeeRepositoryInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

#[AsController]
#[Route(path: '/employee', name: 'employee_')]
class EmployeeController extends AbstractValidatingController
{
    #[Route(name: 'create', methods: [Request::METHOD_POST])]
    public function create(
        EmployeeRepositoryInterface $employeeRepository,
        SerializerInterface $serializer
    ): Response
    {
        $employee = $employeeRepository->save(new Employee(null));
        if (null === $employee->getId()) {
            throw new RuntimeException('Employee Entity has no ID assigned.');
        }

        $responseDto = new CreateEmployeeResponseDto($employee->getId());

        return new JsonResponse($serializer->serialize($responseDto, JsonEncoder::FORMAT), json: true);
    }
}
