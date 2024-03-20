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
 * @mixin StreamableToFloat<TValue>
 */
trait StreamableToFloatTrait
{
    public function mapToFloat(?callable $toFloatMapper = null): FloatStream
    {
        $toFloatMapper ??= static fn(mixed $item): float => (float) $item;

        $generator = fn(): Generator => yield from new MapIterableAggregate(
            $this,
            static fn(mixed $item): float => $toFloatMapper($item)
        );

        return FloatStream::fromGenerator($generator());
    }
}
