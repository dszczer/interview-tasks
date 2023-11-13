<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\EmployeeAllowance\Domain\Service;

use Carbon\CarbonImmutable;
use Kodkod\InterviewTask\EmployeeAllowance\Domain\Entity\Delegation;

/**
 * @final
 *
 * @readonly
 */
class CalculateDelegationAllowance
{
    /**
     * Amount of hours of delegation day to be considered granted for allowance.
     */
    private const FULL_DAY_HOURS = 8;

    /**
     * Allowance bonus of basic rate.
     */
    private const ALLOWANCE_RATE_BONUS_MULTIPLIER = 2;

    /**
     * Allowance is doubled after x amount of calendar days.
     */
    private const ALLOWANCE_RATE_BONUS_AFTER_DAYS = 5;

    public function __construct(private readonly MatchCountryCodeWithAllowanceRate $matchCountryCodeWithAllowanceRate) {
    }

    public function forDelegation(Delegation $delegation): int
    {
        $allowanceRate = $this->matchCountryCodeWithAllowanceRate->getAllowanceRateFor($delegation->getCountryCode());

        // days of full delegation days (8 work hours or more per day)
        $delegationFullDays = $this->getFullDaysOfDelegation($delegation);
        $delegationBonusFullDays = 0;

        if (self::ALLOWANCE_RATE_BONUS_AFTER_DAYS <= $delegationFullDays) {
            $delegationBonusFullDays = $delegationFullDays - self::ALLOWANCE_RATE_BONUS_AFTER_DAYS;
            $delegationFullDays -= $delegationBonusFullDays;
        }

        return $allowanceRate->value * $delegationFullDays
            + $allowanceRate->value * $delegationBonusFullDays * self::ALLOWANCE_RATE_BONUS_MULTIPLIER;
    }

    private function getFullDaysOfDelegation(Delegation $delegation): int
    {
        $workDays = 0;

        // compare day difference
        $carbonStartDateTime = CarbonImmutable::create($delegation->getStartDateTime());
        $carbonEndDateTime = CarbonImmutable::create($delegation->getEndDateTime());
        $carbonProcessingDateTime = $carbonStartDateTime->copy();

        while (0 < $carbonEndDateTime->diffInDays($carbonProcessingDateTime)) {
            if (!$carbonProcessingDateTime->isWeekend()) {
                $workDays++;
            }

            $carbonProcessingDateTime = $carbonProcessingDateTime->addDay();
        }

        // add extra day if full-day work by hour difference
        $startHours = CarbonImmutable::now()->setHour($carbonStartDateTime->hour);
        $endHours = CarbonImmutable::now()->setHour($carbonEndDateTime->hour);
        $hours = $startHours->diffInHours($endHours);

        if (self::FULL_DAY_HOURS <= $hours) {
            $workDays++;
        }

        return $workDays;
    }
}
