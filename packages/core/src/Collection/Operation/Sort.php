<?php

namespace Par\Core\Collection\Operation;

use loophp\iterators\SortIterableAggregate;
use Par\Core\Comparison\Comparator;

/**
 * @template TKey
 * @template TValue
 *
 * @implements Operation<TKey, TValue>
 */
final class Sort implements Operation
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

        yield from new SortIterableAggregate(
            $iterable,
            $comparator(...)
        );
    }
}
