<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream\MixedStream;
use Par\Core\Comparison\Comparators;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class SortedTest extends TestCase
{
    #[Test]
    public function itReturnsSortedStreamUsingNativeByDefault(): void
    {
        $stream = MixedStream::fromIterable([5, 4, 3, 2, 1]);

        self::assertEquals(range(1, 5), $stream->sorted());
    }

    #[Test]
    public function itReturnsSortedStreamUsingCallable(): void
    {
        $stream = MixedStream::fromIterable([5, 4, 3, 2, 1]);

        self::assertEquals(range(1, 5), $stream->sorted(static fn(int $a, int $b): int => $a <=> $b));
    }

    #[Test]
    public function itReturnsSortedStreamUsingComparator(): void
    {
        $stream = MixedStream::fromIterable(range(1, 5));

        self::assertEquals([5, 4, 3, 2, 1], $stream->sorted(Comparators::values()->reversed()));
    }
}
