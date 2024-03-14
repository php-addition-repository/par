<?php

namespace Par\Core\Collection\Stream\Operation;

use Closure;
use loophp\iterators\NormalizeIterableAggregate;
use loophp\iterators\ReduceIterableAggregate;

/**
 * @template TValue
 * @template UValue
 *
 * @implements IntermediateOperation<TValue, UValue>
 */
final class Reduce implements IntermediateOperation
{
    /**
     * @var Closure(TValue, UValue): UValue
     */
    private Closure $accumulator;

    /**
     * @param UValue $initialValue
     * @param callable(TValue, UValue): UValue $accumulator
     */
    public function __construct(private readonly mixed $initialValue, callable $accumulator)
    {
        $this->accumulator = $accumulator(...);
    }

    public function __invoke(iterable $iterable): iterable
    {
        yield from new NormalizeIterableAggregate(
            new ReduceIterableAggregate(
                $iterable,
                $this->accumulator,
                $this->initialValue
            )
        );
    }
}
