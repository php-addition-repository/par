<?php

declare(strict_types=1);

namespace Par\Core\Collection;

use Countable;
use IteratorAggregate;
use Traversable;

/**
 * The root interface in the collection hierarchy. A collection represents a group of objects, known as its elements.
 * Some collections allow duplicate elements and others do not. Some are ordered, and others are unordered.
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @extends IteratorAggregate<TKey, TValue>
 */
interface Collection extends IteratorAggregate, Countable
{
    /**
     * Returns true if this collection contains the specified element.
     *
     * More formally, returns true if and only if this sequence contains at least one element `$e` such that
     * `Values.equals($element, $e)`.
     *
     * @param TValue|mixed $element element whose presence in this vector is to be tested
     *
     * @return bool `true` if this vector contains the specified element
     */
    public function contains(mixed $element): bool;

    /**
     * Returns true if this collection contains all of the elements in the specified iterable.
     *
     * @param iterable<TValue|mixed> $elements an iterable whose elements will be tested for containment in this collection
     *
     * @return bool `true` if this collection contains all the elements from the specified iterable or if no elements were provided
     */
    public function containsAll(iterable $elements): bool;

    /**
     * @return Traversable<TKey, TValue>
     */
    public function getIterator(): Traversable;

    /**
     * Returns `true` if this collection contains no elements.
     *
     * The implementation behind this method might be more optimized then `count($this) === 0` since counting the
     * collection could require iterating over all elements.
     *
     * @return bool `true` if this collection contains no elements
     */
    public function isEmpty(): bool;

    /**
     * Returns a Stream with this collection as its source.
     *
     * @return Stream<TValue>
     */
    public function stream(): Stream;

    /**
     * Returns an array containing all of the elements in this collection.
     *
     * If this collection makes any guarantees as to what order its elements are returned by its iterator, this method
     * must return the elements in the same order.
     *
     * @return array<TKey, TValue> An array representation of this collection
     */
    public function toArray(): array;
}
