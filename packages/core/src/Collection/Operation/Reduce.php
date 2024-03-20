<?php

namespace Par\Core\Collection\Operation;

use Closure;
use loophp\iterators\ReduceIterableAggregate;

/**
 * @template TKey
 * @template TIn
 * @template TOut
 *
 * @implements Operation<TKey, TIn>
 */
final class Reduce implements Operation
{
    /**
     * @var Closure(TIn, TOut): TOut
     */
    private Closure $accumulator;

    /**
     * @param TOut $initialValue
     * @param callable(TIn, TOut): TOut $accumulator
     */
    public function __construct(private readonly mixed $initialValue, callable $accumulator)
    {
        $this->accumulator = $accumulator(...);
    }

    public function __invoke(iterable $iterable): iterable
    {
        yield from new ReduceIterableAggregate(
            $iterable,
            $this->accumulator,
            $this->initialValue
        );
    }
}
