<?php

declare(strict_types=1);

namespace Par\Core;

use DateTimeInterface;

/**
 * Helper class with methods to assist in value handling.
 */
final class Values
{
    /**
     * Determines if two values should be considered equal.
     *
     * - If `$value` implements `Par\Core\ObjectEquality` then `$value->equals($otherValue)` is used.
     * - If `$otherValue` implements `Par\Core\ObjectEquality` then `$otherValue->equals($value)` is used.
     * - When both values implement the `DateTimeInterface` they are considered equal when the equal when formatted
     *   using `RFC3339_EXTENDED`.
     * - Otherwise a strict comparison (`$value === $otherValue`) is used.
     *
     * @param mixed $value The value to test
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
     * Determine if two DateTimeInterface instances can be considered equal.
     *
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

    /**
     * Determines if a value should be considered equal to __one__ or __more__ other values.
     *
     * @param mixed $value The value to test
     * @param mixed ...$otherValues The other values with which to compare
     * @return bool True if value should be considered equal to one or more of the other values
     *
     * @psalm-mutation-free
     */
    public static function equalsOneOf(mixed $value, mixed ...$otherValues): bool
    {
        foreach ($otherValues as $otherValue) {
            if (self::equals($value, $otherValue)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determines if a value should be considered equal to __none__ of the other values.
     *
     * @param mixed $value The value to test
     * @param mixed ...$otherValues The other values with which to compare
     * @return bool True if value should be considered equal to none of the other values
     *
     * @psalm-mutation-free
     */
    public static function equalsNoneOf(mixed $value, mixed ...$otherValues): bool
    {
        foreach ($otherValues as $otherValue) {
            if (self::equals($value, $otherValue)) {
                return false;
            }
        }

        return true;
    }
}
