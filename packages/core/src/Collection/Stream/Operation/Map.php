<?php

namespace Par\Core\Collection\Stream\Operation;

use Closure;
use loophp\iterators\MapIterableAggregate;

/**
 * TODO.
 *
 * @template TIn
 * @template TOut
 *
 * @implements IntermediateOperation<TIn, TOut>
 */
final class Map implements IntermediateOperation
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

    public function __invoke(iterable $iterable): iterable
    {
        yield from new MapIterableAggregate($iterable, $this->mapper);
    }
}
