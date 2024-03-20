<?php

namespace Par\Core\Collection\Operation;

use loophp\iterators\NormalizeIterableAggregate;

/**
 * TODO.
 *
 * @template TKey
 * @template TValue
 *
 * @implements Operation<TKey, TValue>
 */
final class Normalize implements Operation
{
    public function __invoke(iterable $iterable): iterable
    {
        yield from new NormalizeIterableAggregate($iterable);
    }
}
