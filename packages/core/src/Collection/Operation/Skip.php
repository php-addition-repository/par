<?php

namespace Par\Core\Collection\Operation;

use loophp\iterators\LimitIterableAggregate;

/**
 * TODO.
 *
 * @template TKey
 * @template TValue
 *
 * @implements Operation<TKey, TValue>
 */
final class Skip implements Operation
{
    /**
     * @param int<0, max> $amount
     */
    public function __construct(private readonly int $amount)
    {
    }

    public function __invoke(iterable $iterable): iterable
    {
        yield from new LimitIterableAggregate($iterable, $this->amount);
    }
}
