<?php

declare(strict_types=1);

namespace Par\Core;

use DateTimeInterface;

final class Values
{
    /**
     * Determines if values should be considered equal.
     *
     * - If `$value` implements `Par\Core\ObjectEquality` then `$value->equals($otherValue)` is used.
     * - If `$otherValue` implements `Par\Core\ObjectEquality` then `$otherValue->equals($value)` is used.
     * - When both values implement the `DateTimeInterface` they are considered equal when the equal when formatted
     *   using `RFC3339_EXTENDED`.
     * - Otherwise a strict comparison (`$value === $otherValue`) is used.
     *
     * @param mixed $value A value
     * @param mixed $otherValue The other value with which to compare
     *
     * @return bool True if both values should be considered equal
     * @psalm-mutation-free
     */
    public static function equals(mixed $value, mixed $otherValue): bool
    {
        if ($value instanceof ObjectEquality) {
            return $value->equals($otherValue);
        }

        if ($otherValue instanceof ObjectEquality) {
            return $otherValue->equals($value);
        }

        if ($value instanceof DateTimeInterface && $otherValue instanceof DateTimeInterface) {
            return self::compareDateTimes($value, $otherValue);
        }

        return $value === $otherValue;
    }

    /**
     * @param DateTimeInterface $value
     * @param DateTimeInterface $otherValue
     * @return bool
     *
     * @psalm-mutation-free
     * @psalm-suppress ImpureMethodCall
     */
    private static function compareDateTimes(DateTimeInterface $value, DateTimeInterface $otherValue): bool
    {
        $comparisonFormat = DateTimeInterface::RFC3339_EXTENDED;

        return $value->format($comparisonFormat) === $otherValue->format($comparisonFormat);
    }
}
