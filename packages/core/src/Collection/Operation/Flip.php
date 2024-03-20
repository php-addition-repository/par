<?php

namespace Par\Core\Collection\Operation;

/**
 * TODO.
 *
 * @template TKey
 * @template TValue
 *
 * @implements Operation<TKey, TValue>
 */
final class Flip implements Operation
{
    /**
     * @return iterable<TValue, TKey>
     */
    public function __invoke(iterable $iterable): iterable
    {
        foreach ($iterable as $key => $value) {
            yield $value => $key;
        }
    }
}
