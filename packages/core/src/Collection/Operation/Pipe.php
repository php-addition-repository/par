<?php

namespace Par\Core\Collection\Operation;

use Closure;

/**
 * TODO.
 *
 * @template TKey
 * @template TIn
 *
 * @implements Operation<TKey, TIn>
 */
final class Pipe implements Operation
{
    /**
     * @var Operation<TKey, TIn>[]
     */
    private array $operations;

    /**
     * @param Operation<TKey, TIn> ...$operations
     */
    public function __construct(Operation ...$operations)
    {
        $this->operations = $operations;
    }

    public function __invoke(iterable $iterable): iterable
    {
        yield from array_reduce(
            $this->operations,
            static fn(
                callable $a,
                callable $b
            ): Closure => static fn(iterable $iterable): iterable => $b($a($iterable)),
            static fn(iterable $iterable): iterable => $iterable
        )($iterable);
    }
}
