<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Kodkod\InterviewTask\EmployeeAllowance\Infrastructure\Entity\Employee;

class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function save(Employee $employee, bool $flush = true): void
    {
        $em = $this->getEntityManager();
        $em->persist($employee);

        if ($flush) {
            $em->flush();
        }
    }
}
