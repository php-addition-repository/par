<?php

declare(strict_types=1);

namespace Par\Core\Comparison;

use Par\Core\Comparison\Exception\IncomparableException;

/**
 * An object able to compare two values and determine their order in a collection.
 *
 * @template TValue The type of values that can be compared using this comparator
 */
interface Comparator
{
    /**
     * Compares its two arguments for order.
     *
     * @param TValue $v1 The first value to be compared
     * @param TValue $v2 The second value to be compared
     *
     * @throws IncomparableException if arguments are not comparable
     *
     * @psalm-mutation-free
     */
    public function compare(mixed $v1, mixed $v2): Order;

    /**
     * @return Comparator<TValue>
     *
     * @psalm-mutation-free
     */
    public function reversed(): Comparator;

    /**
     * Returns a comparator that compares.
     *
     * @return Comparator<TValue>
     *
     * @psalm-mutation-free
     */
    public function then(Comparator $nextComparator): Comparator;

    /**
     * Returns a comparator that uses the extractor to determine the values to compare.
     *
     * @template UValue
     *
     * @param pure-callable(UValue): TValue $extractor The extractor to use to determine the values to compare
     *
     * @return Comparator<TValue>
     *
     * @psalm-mutation-free
     */
    public function using(callable $extractor): Comparator;
}
