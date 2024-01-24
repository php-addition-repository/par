<?php

declare(strict_types=1);

namespace Par\Core\Collection;

use Par\Core\Exception\IndexOutOfBoundsException;

/**
 * An ordered collection, where the user can access elements by their integer index (position in the list), and search
 * for elements in the list.
 *
 * Some languages refer to this as a `List`.
 *
 * Unlike sets, sequences typically allow duplicate elements. More formally, sequences typically allow pairs of
 * elements `$e1` and `$e2` such that `$e1.equals($e2)`, and they typically allow multiple `null` elements if they
 * allow`null` elements at all. It is not inconceivable that someone might wish to implement a sequence that prohibits
 * duplicates, by throwing runtime exceptions when the user attempts to insert them, but we expect this usage to be
 * rare.
 *
 * @template TValue
 * @template-extends SequencedCollection<TValue>
 */
interface Sequence extends SequencedCollection
{
    /**
     * Returns the element at the specified position in this Vector.
     *
     * @param int<0, max> $index index of the element to return
     *
     * @return TValue element at the specified position
     * @throws IndexOutOfBoundsException if the index is out of range (`$index < 0 || $index >= count($this)`)
     */
    public function get(int $index): mixed;

    /**
     * Returns the index of the first occurrence of the specified element in this sequence, searching forwards from
     * index, or -1 if this list does not contain the element.
     *
     * More formally, returns the lowest index `$i` such that `$i >= $index && Values->equals($element,
     * $this->get($i))`, or -1 if there is no such index.
     *
     * @param TValue           $element element to search for
     * @param null|int<0, max> $index index to start searching from, defaults to first index
     *
     * @return int<-1, max> the index of the first occurrence of the specified element in this sequence at position
     *     `$index` or later, or -1 if this sequence does not contain the element
     * @throws IndexOutOfBoundsException if the index is out of range (`$index < 0 || $index >= count($this)`)
     */
    public function indexOf(mixed $element, int $index = null): int;

    /**
     * Returns the index of the last occurrence of the specified element in this sequence, searching backwards from
     * `$index`, or returns -1 if the element is not found.
     *
     * @param mixed            $element element to search for
     * @param null|int<0, max> $index index to start searching backwards from, defaults to last index
     *
     * @return int<-1, max> the index of the last occurrence of the element at position less than or equal to `$index`
     *     in this sequence, or -1 if this sequence does not contain the element
     * @throws IndexOutOfBoundsException if the index is out of range (`$index < 0 || $index >= count($this)`)
     */
    public function lastIndexOf(mixed $element, int $index = null): int;
}
