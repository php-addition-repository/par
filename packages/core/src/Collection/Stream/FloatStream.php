<?php

declare(strict_types=1);

namespace Par\Core\Collection\Stream;

use Generator;
use Par\Core\Assert;

/**
 * TODO.
 *
 * @extends BaseStream<float>
 * @implements StreamableToInt<float>
 */
final class FloatStream extends BaseStream implements StreamableToInt
{
    /**
     * @use StreamableToIntTrait<float>
     */
    use StreamableToIntTrait;

    /**
     * Create a stream for a range of integers.
     *
     * If `$end` falls
     *
     * @param float $start The initial value
     * @param float $end The inclusive upper bound, must be greater than or equal to start
     * @param float $step step size of each item in range stream, must be zero or more
     *
     * @return FloatStream
     */
    public static function range(float $start = 0.0, float $end = INF, float $step = 1.0): static
    {
        Assert::greaterThanEq($end, $start, 'Range end must be greater than or equal to range start');
        Assert::greaterThanEq($step, 0, 'Range step must be zero or more');

        return new static(static function() use ($step, $end, $start): Generator {
            for ($current = $start; $current <= $end; $current += $step) {
                yield $current;
            }
        });
    }

    public function map(callable $mapper): Stream
    {
        return MixedStream::fromIterable($this)->map($mapper);
    }
}
