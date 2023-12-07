<?php

declare(strict_types=1);

namespace Par\Core;

use DateTime;
use DateTimeImmutable;

/**
 * Helper class with methods to assist in value handling.
 */
final class Values
{
    /**
     * Determines if two values should be considered equal.
     *
     * - If `$value` implements `\Par\Core\ObjectEquality` then `$value->equals($otherValue)` is used.
     * - If `$otherValue` implements `\Par\Core\ObjectEquality` then `$otherValue->equals($value)` is used.
     * - When both values are instances of `\DateTime` `$value == $otherValue` is used.
     * - When both values are instances of `\DateTimeImmutable` `$value == $otherValue` is used.
     * - Otherwise a strict comparison (`$value === $otherValue`) is used.
     *
     * Usage:
     * ```php
     * if (Values::equals($a, $b)) {
     *     // When equal
     * }
     * ```
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

        if ($value instanceof DateTimeImmutable && $otherValue instanceof DateTimeImmutable) {
            return $value == $otherValue;
        }

        if ($value instanceof DateTime && $otherValue instanceof DateTime) {
            return $value == $otherValue;
        }

        return $value === $otherValue;
    }

    /**
     * @param mixed    $value
     * @param iterable $otherValues
     * @param bool     $onMatch
     *
     * @return bool
     * @psalm-mutation-free
     */
    private static function containsValue(mixed $value, iterable $otherValues, bool $onMatch = true): bool
    {
        foreach ($otherValues as $otherValue) {
            if (self::equals($value, $otherValue)) {
                return $onMatch;
            }
        }

        return !$onMatch;
    }

    /**
     * Determines if a value should be considered equal to __one__ or __more__ other values.
     *
     * Usage:
     * ```php
     * if (Values::equalsOneOf($a, $b, $c)) {
     * // When not equal to $b or $c
     * }
     * ```
     *
     * @param mixed $value The value to test
     * @param mixed ...$otherValues The other values with which to compare
     * @return bool True if value should be considered equal to one or more of the other values
     *
     * @psalm-mutation-free
     * @see Values::equals
     */
    public static function equalsOneOf(mixed $value, mixed ...$otherValues): bool
    {
        return self::equalsOneIn($value, $otherValues);
    }

    /**
     * Determines if a value should be considered equal to __none__ of the other values.
     *
     * Usage:
     * ```php
     * if (Values::equalsNoneOf($a, $b, $c)) {
     *     // When not equal to $b and $c
     * }
     * ```
     * @param mixed $value The value to test
     * @param mixed ...$otherValues The other values with which to compare
     * @return bool True if value should be considered equal to none of the other values
     *
     * @psalm-mutation-free
     * @see Values::equals
     */
    public static function equalsNoneOf(mixed $value, mixed ...$otherValues): bool
    {
        return self::equalsNoneIn($value, $otherValues);
    }

    /**
     * Determines if a value should be considered equal to __one__ of the items in the list of other values.
     *
     * Usage:
     * ```php
     * if (Values::equalsOneIn($a, [$b, $c])) {
     *     // When equal to $b or $c
     * }
     * ```
     *
     * @see Values::equals
     *
     * @param iterable $otherValues The list of other values with which to compare
     * @param mixed    $value The value to test
     *
     * @return bool True if value should be considered equal to one of the items in the list of other values
     *
     * @psalm-mutation-free
     */
    public static function equalsOneIn(mixed $value, iterable $otherValues): bool
    {
        return self::containsValue($value, $otherValues);
    }

    /**
     * Determines if a value should be considered equal to __none__ of the items in the list of other values.
     *
     * Usage:
     * ```php
     * if (Values::equalsNoneIn($a, [$b, $c])) {
     *     // When not equal to $b and $c
     * }
     * ```
     *
     * @see Values::equals
     *
     * @param iterable $otherValues The list of other values with which to compare
     * @param mixed    $value The value to test
     *
     * @return bool True if value should be considered equal to none of the items in the list of other values
     *
     * @psalm-mutation-free
     */
    public static function equalsNoneIn(mixed $value, iterable $otherValues): bool
    {
        return self::containsValue($value, $otherValues, false);
    }
}
