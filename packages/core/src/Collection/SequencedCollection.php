<?php

declare(strict_types=1);

namespace Par\Core\Collection;

use Par\Core\Comparison\Comparator;
use Par\Core\Exception\NoSuchElementException;

/**
 * A collection that has a well-defined encounter order, that supports operations at both ends, and that is reversible.
 *
 * @template TValue
 * @extends Collection<int<0, max>, TValue>
 */
interface SequencedCollection extends Collection
{
    /**
     * Gets the first element of this collection.
     *
     * @return TValue the first element
     * @throws NoSuchElementException if this collection is empty
     */
    public function first(): mixed;

    /**
     * Gets the last element of this collection.
     *
     * @return TValue the last element
     * @throws NoSuchElementException if this collection is empty
     */
    public function last(): mixed;

    /**
     * Returns a reverse-ordered version of this collection.
     *
     * @return static<TValue> A new collection with the elements of this collection in reverse order
     */
    public function reversed(): static;

    /**
     * Returns a sorted version of this collection.
     *
     * The sort is stable: this method must not reorder equal elements.
     *
     * @param pure-callable(TValue,TValue):int<-1,1>|Comparator<TValue>|null $comparator a non-interfering comparator
     *      to be used to determine the new order of the elements from this sequence
     *
     * @return static<TValue> A new collection with the elements sorted according to the provided comparator
     */
    public function sorted(callable|Comparator $comparator = null): static;
}
