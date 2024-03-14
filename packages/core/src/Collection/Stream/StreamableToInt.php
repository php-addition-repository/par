<?php

declare(strict_types=1);

namespace Par\Core\Collection\Stream;

/**
 * TODO.
 *
 * @internal
 *
 * @template TValue
 *
 * @mixin Stream<TValue>
 */
interface StreamableToInt
{
    /**
     * TODO.
     *
     * @param callable(TValue): int|null $toIntMapper TODO
     */
    public function mapToInt(?callable $toIntMapper = null): IntStream;
}
