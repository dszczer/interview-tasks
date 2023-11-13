<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Kodkod\InterviewTask\EmployeeAllowance\Infrastructure\Entity\Delegation;

class DelegationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Delegation::class);
    }

    public function save(Delegation $delegation, bool $flush = true): void
    {
        $em = $this->getEntityManager();
        $em->persist($delegation);

        if ($flush) {
            $em->flush();
        }
    }

    /**
     * @return iterable<Delegation>
     */
    public function getByEmployeeId(int $id): iterable
    {
        return $this->findBy(['employee' => $id]);
    }
}
