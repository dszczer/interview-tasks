<?php

declare(strict_types=1);

namespace Kodkod\InterviewTask\CurrencyExchange;

use function abs;

use const PHP_FLOAT_EPSILON;

/**
 * Not instantiable.
 *
 * Helper class for float comparison.
 */
final readonly class FloatUtil
{
    /**
     * Delta must be twice the epsilon for edge case like dividing by 1.
     */
    public const SAFE_DELTA = PHP_FLOAT_EPSILON * 2;

    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    public static function isEqual(float $a, float $b): bool
    {
        return abs($a - $b) < self::SAFE_DELTA;
    }
}
