<?php

declare(strict_types=1);

namespace Par\Core\Collection;

use Countable;
use IteratorAggregate;
use Traversable;

/**
 * TODO
 * @template TKey
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
