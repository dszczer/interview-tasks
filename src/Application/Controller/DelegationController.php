<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Application\Controller;

use DateTimeImmutable;
use Exception;
use Kodkod\InterviewTask\EmployeeAllowance\Application\Dto\CreateDelegationRequestDto;
use Kodkod\InterviewTask\EmployeeAllowance\Application\Dto\CreateDelegationResponseDto;
use Kodkod\InterviewTask\EmployeeAllowance\Application\Dto\ItemDelegationResponseDto;
use Kodkod\InterviewTask\EmployeeAllowance\Application\Dto\ListDelegationRequestDto;
use Kodkod\InterviewTask\EmployeeAllowance\Application\Dto\ListDelegationResponseDto;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Entity\Delegation;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Entity\Employee;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Repository\DelegationRepositoryInterface;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Repository\EmployeeRepositoryInterface;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Service\CalculateDelegationAllowance;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Specification\CountryCodeSpecification;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Specification\CurrencySpecification;
use Phpro\ApiProblem\Exception\ApiProblemException;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use UnexpectedValueException;

#[AsController]
#[Route(path: '/delegation', name: 'delegation_')]
class DelegationController extends AbstractValidatingController
{
    /**
     * @throws ApiProblemException
     * @throws Exception
     */
    #[Route(name: 'create', methods: [Request::METHOD_POST])]
    public function create(
        #[MapRequestPayload] CreateDelegationRequestDto $requestDto,
        DelegationRepositoryInterface $delegationRepository,
        EmployeeRepositoryInterface $employeeRepository,
        SerializerInterface $serializer
    ): Response {
        $this->validateRequest($requestDto);

        $employee = $employeeRepository->getById($requestDto->employeeId);
        if (null === $employee) {
            throw new UnexpectedValueException(sprintf('Expected instance of "%s", got null.', Employee::class));
        }

        $delegation = new Delegation(
            null,
            new DateTimeImmutable($requestDto->startDateTime),
            new DateTimeImmutable($requestDto->endDateTime),
            $employee,
            CountryCodeSpecification::from($requestDto->countryCodeSpecification)
        );

        $delegation = $delegationRepository->save($delegation);
        if (null === $delegation->getId()) {
            throw new RuntimeException('Delegation entity has no ID assigned.');
        }

        $responseDto = new CreateDelegationResponseDto($delegation->getId());

        return new JsonResponse($serializer->serialize($responseDto, JsonEncoder::FORMAT), json: true);
    }

    /**
     * @throws ApiProblemException
     */
    #[Route(path: '/list', name: 'list', methods: [Request::METHOD_GET])]
    public function list(
        #[MapQueryString] ListDelegationRequestDto $requestDto,
        DelegationRepositoryInterface $delegationRepository,
        SerializerInterface $serializer,
        CalculateDelegationAllowance $calculateDelegationAllowance
    ): Response {
        $this->validateRequest($requestDto);

        $delegations = $delegationRepository->getAllByEmployeeId($requestDto->employeeId);

        $delegationDtoItems = [];
        foreach ($delegations as $delegation) {
            $delegationDtoItems[] = new ItemDelegationResponseDto(
                $delegation->getStartDateTime()->format('Y-m-d H:i:s'),
                $delegation->getEndDateTime()->format('Y-m-d H:i:s'),
                $delegation->getCountryCode()->value,
                $calculateDelegationAllowance->forDelegation($delegation),
                CurrencySpecification::PLN->value
            );
        }

        return new JsonResponse($serializer->serialize($delegationDtoItems, JsonEncoder::FORMAT), json: true);
    }
}
