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
 * $stream = \Par\Core\Collection\Stream\MixedStream::fromIterable(array_shuffle(range(1, 10));
 * $stream->sorted(Comparators::integers()); // [1,2,3,4,5,6,7,8,9,10]
 * ```
 *
 * @template T the type of value that can be compared
 *
 * @phpstan-type IntFunction callable(T): int
 * @phpstan-type FloatFunction callable(T): float
 * @phpstan-type StringFunction callable(T): (string|Stringable)
 */
final class Comparators
{
    /**
     * Returns `true` if value comes after otherValue.
     *
     * This is the same as:
     * ```
     * $comparator->compare($value, $otherValue) === Order::Greater;
     * ```
     *
     * @param T $value The value to test
     * @param T $otherValue The other value to test against
     * @param Comparator<T>|null $comparator Optional comparator to use, defaults to `Par\Core\Comparison\Comparators::values()`
     *
     * @return bool `true` if value comes after otherValue
     */
    public static function comesAfter(mixed $value, mixed $otherValue, ?Comparator $comparator = null): bool
    {
        $comparator ??= self::values();

        return Order::Greater === $comparator->compare($value, $otherValue);
    }

    /**
     * Returns `true` if value comes after or equals otherValue.
     *
     * This is the same as:
     * ```
     * $comparator->compare($value, $otherValue) !== Order::Lesser;
     * ```
     *
     * @param T $value the value to test
     * @param T $otherValue the other value to test against
     * @param Comparator<T>|null $comparator Optional comparator to use, defaults to `Par\Core\Comparison\Comparators::values()`
     *
     * @return bool `true` if value comes after or equals otherValue
     */
    public static function comesAfterOrEquals(mixed $value, mixed $otherValue, ?Comparator $comparator = null): bool
    {
        return !self::comesBefore($value, $otherValue, $comparator);
    }

    /**
     * Returns `true` if value comes before otherValue.
     *
     * This is the same as:
     * ```
     * $comparator->compare($value, $otherValue) === Order::Lesser;
     * ```
     *
     * @param T $value the value to test
     * @param T $otherValue the other value to test against
     * @param Comparator<T>|null $comparator Optional comparator to use, defaults to `Par\Core\Comparison\Comparators::values()`
     *
     * @return bool `true` if value comes before otherValue
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
     * @param T $value The value to test
     * @param T $otherValue the other value to test against
     * @param Comparator<T>|null $comparator Optional comparator to use, defaults to `Par\Core\Comparison\Comparators::values()`
     *
     * @return bool `true` if value comes before or equals otherValue
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
     * @param FloatFunction|null $extractor an extractor that returns the `float` value to use in the comparison
     *
     * @return ($extractor is callable ? Comparator<T> : Comparator<float>)
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
     * @param IntFunction|null $extractor an extractor that returns the `integer` value to use in the comparison
     *
     * @return ($extractor is callable ? Comparator<T> : Comparator<int>)
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
     * Returns a comparator to compare two `string` or `Stringable` values using a natural case insensitive order.
     *
     * The comparator will automatically implement a guard to make sure both values are strings or objects implementing
     * the `Stringable` interface.
     *
     * @param StringFunction|null $extractor an extractor that returns the `string` or `Stringable` value to use in the comparison
     *
     * @return ($extractor is callable ? Comparator<T> :Comparator<string|Stringable>)
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
     * Returns a comparator to compare two `string` or `Stringable` values using a natural case sensitive order.
     *
     * The comparator will automatically implement a guard to make sure both values are strings or objects implementing
     * the `Stringable` interface.
     *
     * @param StringFunction|null $extractor an extractor that returns the `string` or `Stringable` value to use in the comparison
     *
     * @return ($extractor is callable ? Comparator<T> : Comparator<string|Stringable>)
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
     * Returns a comparator to compare two `string` or `Stringable` values.
     *
     * The comparator will automatically implement a guard to make sure both values are strings or objects implementing
     * the `Stringable` interface.
     *
     * @param StringFunction|null $extractor an extractor that returns the `string` or `Stringable` value to use in the comparison
     *
     * @return ($extractor is callable ? Comparator<T> : Comparator<string|Stringable>)
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
     * Returns a comparator that uses the objects comparator if possible, otherwise falls back on the native comparison `$a <=> $b`.
     *
     * @param callable(T): mixed|null $extractor an extractor that returns the value to use in the comparison
     *
     * @return ($extractor is callable ? Comparator<T> : Comparator<mixed>)
     */
    public static function values(?callable $extractor = null): Comparator
    {
        return self::decorateWithExtractor(
            self::with(
                static function(mixed $value, mixed $otherValue): Order {
                    if ($value instanceof Comparable) {
                        return $value->compare($otherValue);
                    }

                    if ($otherValue instanceof Comparable) {
                        return $otherValue->compare($value)->invert();
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
     * @param callable(T, T): (Order|int<-1,1>) $comparator The comparator callback
     *
     * @return Comparator<T>
     */
    public static function with(callable $comparator): Comparator
    {
        if ($comparator instanceof Comparator) {
            return $comparator;
        }

        return new CallableComparator($comparator);
    }

    /**
     * @param Comparator<mixed> $decorated
     * @param callable(T):mixed|null $extractor
     *
     * @return ($extractor is callable ? Comparator<T> : Comparator<mixed>)
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
