<?php

namespace Par\Core\Collection\Stream\Operation;

use loophp\iterators\LimitIterableAggregate;
use loophp\iterators\NormalizeIterableAggregate;

/**
 * @template TValue
 *
 * @implements IntermediateOperation<TValue, TValue>
 */
final class Limit implements IntermediateOperation
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

        yield from new NormalizeIterableAggregate(
            new LimitIterableAggregate(
                iterable: $iterable,
                limit: $this->amount
            )
        );
    }
}
