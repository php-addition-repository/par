<?php

namespace Par\Core\Collection\Operation;

/**
 * TODO.
 *
 * @template TKey
 * @template TIn
 */
interface Operation
{
    /**
     * @param iterable<TKey, TIn> $iterable
     *
     * @return iterable<TKey, TIn>
     */
    public function __invoke(iterable $iterable): iterable;
}
