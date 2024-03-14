<?php

namespace Par\Core\Collection\Stream\Operation;

use Closure;

/**
 * TODO.
 *
 * @template TValue
 *
 * @implements IntermediateOperation<TValue, TValue>
 */
final class Apply implements IntermediateOperation
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
        foreach ($iterable as $item) {
            $action($item);

            yield $item;
        }
    }
}
