<?php

declare(strict_types=1);

namespace Par\Core\Collection\Stream;

use Generator;
use loophp\iterators\MapIterableAggregate;

/**
 * TODO.
 *
 * @internal
 *
 * @template TValue
 *
 * @mixin StreamableToInt<TValue>
 */
trait StreamableToIntTrait
{
    public function mapToInt(?callable $toIntMapper = null): IntStream
    {
        $toIntMapper ??= static fn(mixed $item): int => (int) $item;

        $generator = fn(): Generator => yield from new MapIterableAggregate(
            $this,
            static fn(mixed $item): int => $toIntMapper($item)
        );

        return IntStream::fromGenerator($generator());
    }
}
