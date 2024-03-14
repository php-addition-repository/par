<?php

declare(strict_types=1);

namespace Par\Core\Collection\Stream;

use Generator;
use Par\Core\Assert;

/**
 * TODO.
 *
 * @extends BaseStream<int>
 * @implements StreamableToFloat<int>
 */
final class IntStream extends BaseStream implements StreamableToFloat
{
    /**
     * @use StreamableToFloatTrait<int>
     */
    use StreamableToFloatTrait;

    /**
     * Create a stream for a range of integers.
     *
     * @param int $start The initial value
     * @param int $end The inclusive upper bound, must be greater than or equal to start
     * @param int<0, max> $step step size of each item in range stream, must be zero or more
     *
     * @return IntStream
     */
    public static function range(int $start = 0, int $end = PHP_INT_MAX, int $step = 1): static
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
