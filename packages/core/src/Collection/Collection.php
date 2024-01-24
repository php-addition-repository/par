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
     * {@inheritDoc}
     * @return Traversable<TKey, TValue>
     */
    public function getIterator(): Traversable;
}
