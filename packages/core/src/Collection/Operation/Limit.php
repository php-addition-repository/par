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
final class Limit implements Operation
{
    /**
     * @param int<0, max> $amount
     */
    public function __construct(private readonly int $amount)
    {
    }

    public function __invoke(iterable $iterable): iterable
    {
        if (0 === $this->amount) {
            return $iterable;
        }

        yield from new LimitIterableAggregate(
            iterable: $iterable,
            limit: $this->amount
        );
    }
}
