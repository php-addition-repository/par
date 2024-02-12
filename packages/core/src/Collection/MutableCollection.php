<?php

declare(strict_types=1);

namespace Par\Core\Collection;

/**
 * A collection that can be mutated by adding and/or removing elements.
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @extends Collection<TKey, TValue>
 */
interface MutableCollection extends Collection
{
    /**
     * Ensures that this collection contains the specified element.
     *
     * @param TValue $element element to be appended to this collection
     *
     * @return bool `true` if this collection changed as a result of the call
     */
    public function add(mixed $element): bool;

    /**
     * Ensures that this collection contains all of the elements in the specified iterable, elements that are not contained are added in the order as they are provided.
     *
     * @param iterable<TValue> $elements iterable containing elements to be added to this collection
     *
     * @return bool `true` if this collection changed as a result of the call
     */
    public function addAll(iterable $elements): bool;

    /**
     * Removes the first occurrence of the specified element from this list, if it is present.
     *
     * @param TValue $element element to be removed from this list, if present
     *
     * @return bool `true` if the element was present in the sequence
     */
    public function remove(mixed $element): bool;

    /**
     * Removes all of the elements of this sequence that satisfy the given predicate.
     *
     * @param callable(TValue): bool $predicate a non-interfering predicate which returns true for elements to be
     *                                          removed
     *
     * @return bool `true` if any elements were removed
     */
    public function removeIf(callable $predicate): bool;
}
