<?php

declare(strict_types=1);

namespace Par\Core\Collection\Stream;

/**
 * TODO.
 *
 * @template TValue
 *
 * @extends BaseStream<TValue>
 * @implements StreamableToInt<TValue>
 * @implements StreamableToFloat<TValue>
 */
final class MixedStream extends BaseStream implements StreamableToInt, StreamableToFloat
{
    /**
     * @use StreamableToIntTrait<TValue>
     */
    use StreamableToIntTrait;

    /**
     * @use StreamableToFloatTrait<TValue>
     */
    use StreamableToFloatTrait;
}
