<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Infrastructure\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

trait IdFieldTrait
{
    #[Id]
    #[Column(type: 'integer', nullable: false)]
    #[GeneratedValue]
    private ?int $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
