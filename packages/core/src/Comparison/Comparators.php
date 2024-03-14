<?php

declare(strict_types=1);

namespace Par\Core\Comparison;

use Stringable;

/**
 * Comparators can be used to determine the order of values in a collection.
 *
 * All the methods return a function that accepts 2 values and returns their `Order`.
 *
 * Usage example:
 * ```php
 * $stream = Stream::fromIterable(array_shuffle(range(1, 10));
 * $stream->sort(Comparators::integers()); // [1,2,3,4,5,6,7,8,9,10]
 * ```
 */
final class Comparators
{
    /**
     * Returns true if value comes after otherValue.
     *
     * This is the same as:
     * ```
     * $comparator->compare($value, $otherValue) === Order::Greater;
     * ```
     *
     * @param mixed $value The value to test
     * @param mixed $otherValue The other value to test against
     * @param Comparator|null $comparator Optional comparator to use, defaults to
     *                                    `\Par\Core\Comparison\Comparators::values()`
     */
    public static function comesAfter(mixed $value, mixed $otherValue, ?Comparator $comparator = null): bool
    {
        $comparator ??= self::values();

        return Order::Greater === $comparator->compare($value, $otherValue);
    }

    /**
     * Returns true if value comes after or equals otherValue.
     *
     * This is the same as:
     * ```
     * $comparator->compare($value, $otherValue) !== Order::Lesser;
     * ```
     *
     * @param mixed $value The value to test
     * @param mixed $otherValue The other value to test against
     * @param Comparator|null $comparator Optional comparator to use, defaults to
     *                                    `\Par\Core\Comparison\Comparators::values()`
     */
    public static function comesAfterOrEquals(mixed $value, mixed $otherValue, ?Comparator $comparator = null): bool
    {
        return !self::comesBefore($value, $otherValue, $comparator);
    }

    /**
     * Returns true if value comes before otherValue.
     *
     * This is the same as:
     * ```
     * $comparator->compare($value, $otherValue) === Order::Lesser;
     * ```
     *
     * @param mixed $value The value to test
     * @param mixed $otherValue the other value to test against
     * @param Comparator|null $comparator Optional comparator to use, defaults to
     *                                    `\Par\Core\Comparison\Comparators::values()`
     */
    public static function comesBefore(mixed $value, mixed $otherValue, ?Comparator $comparator = null): bool
    {
        $comparator ??= self::values();

        return Order::Lesser === $comparator->compare($value, $otherValue);
    }

    /**
     * Returns true if value comes before or equals otherValue.
     *
     * This is the same as:
     * ```
     * $comparator->compare($value, $otherValue) !== Order::Greater;
     * ```
     *
     * @param mixed $value The value to test
     * @param mixed $otherValue the other value to test against
     * @param Comparator|null $comparator Optional comparator to use, defaults to `\Par\Core\Comparison\Comparators::values()`
     */
    public static function comesBeforeOrEquals(mixed $value, mixed $otherValue, ?Comparator $comparator = null): bool
    {
        return !self::comesAfter($value, $otherValue, $comparator);
    }

    /**
     * Returns a comparator to compare two `float` values.
     *
     * The comparator will automatically implement a guard to make sure both values are floats.
     *
     * @template TValue
     *
     * @param callable(TValue): float|null $extractor an extractor that returns the `float` value to use in the comparison
     *
     * @return ($extractor is null ? Comparator<float> : Comparator<TValue>)
     */
    public static function floats(?callable $extractor = null): Comparator
    {
        return self::decorateWithExtractor(
            new GuardComparator(
                self::values(),
                static fn(mixed $value): bool => is_float($value) && !is_infinite($value),
                'a finite float.'
            ),
            $extractor
        );
    }

    /**
     * Returns a comparator to compare two `integer` values.
     *
     * The comparator will automatically implement a guard to make sure both values are integers.
     *
     * @template TValue
     *
     * @param callable(TValue): int|null $extractor an extractor that returns the `integer` value to use in the comparison
     *
     * @return ($extractor is null ? Comparator<int> : Comparator<TValue>)
     */
    public static function integers(?callable $extractor = null): Comparator
    {
        return self::decorateWithExtractor(
            new GuardComparator(
                self::values(),
                static fn(mixed $value): bool => is_int($value),
                'an integer.'
            ),
            $extractor
        );
    }

    /**
     * Returns a comparator to compare two `string`s or `Stringable` objects using a natural case insensitive order.
     *
     * The comparator will automatically implement a guard to make sure both values are strings or objects implementing
     * the `Stringable` interface.
     *
     * @template TValue
     *
     * @param callable(TValue): (string|Stringable)|null $extractor an extractor that returns the `string` or `Stringable` value to use in the comparison
     *
     * @return ($extractor is null ? Comparator<string|Stringable> : Comparator<TValue>)
     */
    public static function naturalCaseSensitiveOrder(?callable $extractor = null): Comparator
    {
        return self::decorateWithExtractor(
            new GuardComparator(
                self::with(
                    static fn(string|Stringable $value, string|Stringable $otherValue): Order => Order::from(
                        strnatcmp((string) $value, (string) $otherValue)
                    )
                ),
                self::getStringPredicate(),
                'both values must be a string or an object implementing the Stringable interface.'
            ),
            $extractor
        );
    }

    /**
     * Returns a comparator to compare two `string`s or `Stringable` objects using a natural case sensitive order.
     *
     * The comparator will automatically implement a guard to make sure both values are strings or objects implementing
     * the `Stringable` interface.
     *
     * @template TValue
     *
     * @param callable(TValue): (string|Stringable)|null $extractor an extractor that returns the `string` or `Stringable` value to use in the comparison
     *
     * @return ($extractor is null ? Comparator<string|Stringable> : Comparator<TValue>)
     */
    public static function naturalOrder(?callable $extractor = null): Comparator
    {
        return self::decorateWithExtractor(
            new GuardComparator(
                self::with(
                    static fn(string|Stringable $value, string|Stringable $otherValue): Order => Order::from(
                        strnatcasecmp((string) $value, (string) $otherValue)
                    )
                ),
                self::getStringPredicate(),
                'both values must be a string or an object implementing the Stringable interface.'
            ),
            $extractor
        );
    }

    /**
     * Returns a comparator that compares two `string`s or `Stringable` objects.
     *
     * The comparator will automatically implement a guard to make sure both values are strings or objects implementing
     * the `Stringable` interface.
     *
     * @template TValue
     *
     * @param callable(TValue): (string|Stringable)|null $extractor an extractor that returns the `string` or `Stringable` value to use in the comparison
     *
     * @return ($extractor is null ? Comparator<string|Stringable> : Comparator<TValue>)
     */
    public static function strings(?callable $extractor = null): Comparator
    {
        return self::decorateWithExtractor(
            new GuardComparator(
                self::with(
                    static fn(string|Stringable $value, string|Stringable $otherValue): Order => Order::from(
                        (string) $value <=> (string) $otherValue
                    )
                ),
                self::getStringPredicate(),
                'both values must be a string or an object implementing the Stringable interface.'
            ),
            $extractor
        );
    }

    /**
     * Returns a comparator that uses the objects comparator if possible, otherwise falls back on the native comparison
     * `$a <=> $b`.
     *
     * @template TValue
     * @template UValue
     *
     * @param callable(TValue): UValue|null $extractor an extractor that returns the value to use in the comparison
     *
     * @return Comparator<TValue>
     */
    public static function values(?callable $extractor = null): Comparator
    {
        return self::decorateWithExtractor(
            self::with(
                static function(mixed $value, mixed $otherValue): Order {
                    if ($value instanceof Comparable) {
                        return $value->compare($otherValue);
                    }

                    return Order::from($value <=> $otherValue);
                }
            ),
            $extractor
        );
    }

    /**
     * Returns a comparator that uses the comparator callback to determine value order.
     *
     * Internally this is the same as:
     * ```php
     * $comparator = new CallableComparator(
     *     $comparator
     * );
     * ```
     *
     * @template TValue
     *
     * @param callable(TValue, TValue): (Order|int<-1,1>) $comparator The comparator callback
     *
     * @return Comparator<TValue>
     */
    public static function with(callable $comparator): Comparator
    {
        return new CallableComparator($comparator);
    }

    /**
     * @template TValue
     * @template UValue
     *
     * @param callable(TValue):UValue|null $extractor
     *
     * @return Comparator<TValue>
     */
    private static function decorateWithExtractor(Comparator $decorated, ?callable $extractor): Comparator
    {
        if ($extractor) {
            return $decorated->using($extractor);
        }

        return $decorated;
    }

    /**
     * @return callable(mixed): bool
     */
    private static function getStringPredicate(): callable
    {
        return static fn(mixed $value): bool => is_string($value) || $value instanceof Stringable;
    }
}
