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
final class Apply implements Operation
{
    /**
     * @var Closure(TValue): void
     */
    private Closure $action;

    /**
     * @param callable(TValue):void $action
     */
    public function __construct(callable $action)
    {
        $this->action = $action(...);
    }

    public function __invoke(iterable $iterable): iterable
    {
        $action = $this->action;
        foreach ($iterable as $key => $item) {
            $action($item);

            yield $key => $item;
        }
    }
}
