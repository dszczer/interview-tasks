<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Domain\IdAware;

use Kodkod\InterviewTask\EmployeeAllowance\Domain\Exception\NotNaturalIntegerValueException;

trait IdAwareTrait
{
    private ?int $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    private function selfValidateIdAware(): void
    {
        if (null !== $this->id && 1 > $this->id) {
            throw new NotNaturalIntegerValueException($this->id);
        }
    }
}
