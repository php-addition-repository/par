<?php

declare(strict_types=1);

namespace Par\Core\Collection;

use Par\Core\Comparison\Comparator;
use Par\Core\Exception\IndexOutOfBoundsException;

/**
 * A mutable sequence, a ordered collection where the user has precise control over where in the sequence each element
 * is inserted.
 *
 * @see Sequence
 * @template TValue
 * @template-extends Sequence<TValue>
 * @template-extends MutableSequencedCollection<TValue>
 */
interface MutableSequence extends Sequence, MutableSequencedCollection
{
    /**
     * Reverses all the elements of this sequence.
     *
     * @return static<TValue> This sequence with all its elements in reverse order
     */
    public function reverse(): static;

    /**
     * Replaces the element at the specified position in this sequence with the specified element.
     *
     * @param int<0, max> $index index of the element to replace
     * @param TValue      $element element to be stored at the specified position
     *
     * @return TValue the element previously at the specified position
     * @throws IndexOutOfBoundsException if the index is out of range (`$index < 0 || $index >= count($this)`)
     */
    public function set(int $index, mixed $element): mixed;

    /**
     * Inserts all of the elements in the specified iterable into this Sequence at the specified position.
     *
     * Shifts the element currently at that position (if any) and any subsequent elements to the right (increases their
     * indices). The new elements will appear in the Sequence in the order that they are returned by the specified
     * iterable.
     *
     * @param int<0, max>      $index index at which to insert the first element from the specified collection
     * @param iterable<TValue> $elements elements to be inserted into this Sequence
     *
     * @return bool `true` if this sequence changed as a result of this call
     * @throws IndexOutOfBoundsException if the index is out of range (`$index < 0 || $index >= count($this)`)
     */
    public function setAll(int $index, iterable $elements): bool;

    /**
     * Sorts this sequence according to the order induced by the specified Comparator.
     *
     * The sort is stable: this method must not reorder equal elements.
     *
     * @param pure-callable(TValue, TValue): int<-1,1>|Comparator<TValue>|null $comparator a non-interfering comparator
     *       to be used to determine the new order of the elements from this sequence
     *
     * @return static<TValue> This sequence with all its elements sorted according to the provided comparator
     */
    public function sort(callable|Comparator $comparator = null): static;

    /**
     * Removes the element at the specified position in this sequence.
     *
     * Shifts any subsequent elements to the left (subtracts one from their indices). Returns the element that was
     * removed from the list.
     *
     * @param int<0, max> $index the index of the element to be removed
     *
     * @return TValue the element previously at the specified position
     * @throws IndexOutOfBoundsException if the index is out of range (`$index < 0 || $index >= count($this)`)
     */
    public function unset(int $index): mixed;
}
