<?php

namespace Par\Core\Collection\Stream\Operation;

use Closure;

/**
 * TODO.
 *
 * @template TValue
 *
 * @implements IntermediateOperation<TValue, bool>
 */
final class Every implements IntermediateOperation
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
        $predicate = $this->predicate;
        foreach ($iterable as $item) {
            if (false === $predicate($item)) {
                return yield false;
            }
        }

        yield true;
    }
}
