<?php

namespace Par\Core\Collection\Operation;

use Closure;

/**
 * TODO.
 *
 * @template TKey
 * @template TValue
 *
 * @implements Operation<TKey, TValue>
 */
final class Every implements Operation
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

    /**
     * @return iterable<TKey, bool>
     */
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
