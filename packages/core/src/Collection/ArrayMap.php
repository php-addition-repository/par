<?php

declare(strict_types=1);

namespace Par\Core\Collection;

use BadMethodCallException;

/**
 * TODO.
 *
 * @template TKey of array-key
 * @template TValue
 *
 * @extends AbstractArrayMap<TKey, TValue>
 */
final class ArrayMap extends AbstractArrayMap
{
    public function offsetSet(mixed $offset, mixed $value): void
    {
        // TODO replace with Core exception
        throw new BadMethodCallException();
    }

    public function offsetUnset(mixed $offset): void
    {
        // TODO replace with Core exception
        throw new BadMethodCallException();
    }
}
