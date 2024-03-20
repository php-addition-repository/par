<?php

namespace Par\Core\Collection\Operation;

use Closure;
use loophp\iterators\MapIterableAggregate;

/**
 * TODO.
 *
 * @template TKey
 * @template TIn
 * @template TOut
 *
 * @implements Operation<TKey, TIn>
 */
final class Map implements Operation
{
    /**
     * @var Closure(TIn): TOut
     */
    private Closure $mapper;

    /**
     * @param callable(TIn): TOut $mapper
     */
    public function __construct(callable $mapper)
    {
        $this->mapper = $mapper(...);
    }

    /**
     * @return iterable<TKey, TOut>
     */
    public function __invoke(iterable $iterable): iterable
    {
        yield from new MapIterableAggregate($iterable, $this->mapper);
    }
}
