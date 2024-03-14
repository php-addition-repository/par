<?php

namespace Par\Core\Collection\Stream\Operation;

/**
 * TODO.
 *
 * @template TIn
 * @template TOut
 */
interface IntermediateOperation
{
    /**
     * @param iterable<TIn> $iterable
     *
     * @return iterable<TOut>
     */
    public function __invoke(iterable $iterable): iterable;
}
