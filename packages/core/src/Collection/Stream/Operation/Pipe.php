<?php

namespace Par\Core\Collection\Stream\Operation;

use Closure;

/**
 * TODO.
 *
 * @template TIn
 * @template TOut
 *
 * @implements IntermediateOperation<TIn, TOut>
 */
final class Pipe implements IntermediateOperation
{
    /**
     * @var (Closure(iterable<TIn>): iterable<TOut>)[]
     */
    private array $operations;

    /**
     * @param callable(iterable<TIn>): iterable<TOut> ...$operations
     */
    public function __construct(callable ...$operations)
    {
        $this->operations = $operations;
    }

    public function __invoke(iterable $iterable): iterable
    {
        return array_reduce(
            $this->operations,
            static fn(
                callable $a,
                callable $b
            ): Closure => static fn(iterable $iterable): iterable => $b($a($iterable)),
            static fn(iterable $iterable): iterable => $iterable
        )($iterable);
    }
}
