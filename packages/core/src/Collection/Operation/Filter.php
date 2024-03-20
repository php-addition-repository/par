<?php

namespace Par\Core\Collection\Operation;

use Closure;
use loophp\iterators\FilterIterableAggregate;

/**
 * TODO.
 *
 * @template TKey
 * @template TValue
 *
 * @implements Operation<TKey, TValue>
 */
final class Filter implements Operation
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
