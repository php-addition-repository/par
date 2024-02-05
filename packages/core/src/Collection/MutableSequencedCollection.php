<?php

declare(strict_types=1);

namespace Par\Core\Collection;

use Par\Core\Exception\NoSuchElementException;

/**
 * A mutable sequenced collection.
 *
 * @see SequencedCollection
 *
 * @template TValue
 *
 * @extends SequencedCollection<TValue>
 * @extends MutableCollection<int<0, max>, TValue>
 */
interface MutableSequencedCollection extends SequencedCollection, MutableCollection
{
    /**
     * Adds an element as the first element of this collection.
     *
     * @param TValue $element the element to be added
     */
    public function addFirst(mixed $element): void;

    /**
     * Adds an element as the last element of this collection.
     *
     * @param TValue $element the element to be added
     */
    public function addLast(mixed $element): void;

    /**
     * Removes and returns the first element of this sequence.
     *
     * @return TValue the removed element
     *
     * @throws NoSuchElementException if this sequence is empty
     */
    public function removeFirst(): mixed;

    /**
     * Removes and returns the last element of this sequence.
     *
     * @return TValue the removed element
     *
     * @throws NoSuchElementException if this sequence is empty
     */
    public function removeLast(): mixed;
}
