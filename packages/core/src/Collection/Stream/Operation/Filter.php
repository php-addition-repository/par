<?php

namespace Par\Core\Collection\Stream\Operation;

use Closure;
use loophp\iterators\FilterIterableAggregate;

/**
 * @template TValue
 *
 * @implements IntermediateOperation<TValue, TValue>
 */
final class Filter implements IntermediateOperation
{
    /**
     * @var Closure(TValue): bool
     */
    private Closure $predicate;

    /**
     * @param callable(TValue):bool $predicate
     */
    public function __construct(callable $predicate)
    {
        $this->predicate = $predicate(...);
    }

    public function __invoke(iterable $iterable): iterable
    {
        yield from new FilterIterableAggregate($iterable, $this->predicate);
    }
}
