<?php

namespace Par\Core\Collection\Stream\Operation;

use loophp\iterators\NormalizeIterableAggregate;
use loophp\iterators\SortIterableAggregate;
use Par\Core\Comparison\Comparator;

/**
 * @template TValue
 *
 * @implements IntermediateOperation<TValue, TValue>
 */
final class Sort implements IntermediateOperation
{
    /**
     * @param Comparator<TValue> $comparator
     */
    public function __construct(private readonly Comparator $comparator)
    {
    }

    public function __invoke(iterable $iterable): iterable
    {
        $comparator = $this->comparator;

        yield from new NormalizeIterableAggregate(
            new SortIterableAggregate(
                $iterable,
                $comparator(...)
            )
        );
    }
}
