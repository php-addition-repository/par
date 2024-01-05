<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream;
use Par\Core\Comparison\Comparators;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SortedTest extends TestCase
{
    #[Test]
    public function itReturnsSortedStreamUsingNativeByDefault(): void
    {
        $stream = Stream::fromIterable([5, 4, 3, 2, 1]);

        $this->assertEquals(range(1, 5), $stream->sorted());
    }

    #[Test]
    public function itReturnsSortedStreamUsingCallable(): void
    {
        $stream = Stream::fromIterable([5, 4, 3, 2, 1]);

        $this->assertEquals(range(1, 5), $stream->sorted(static fn(int $a, int $b): int => $a <=> $b));
    }

    #[Test]
    public function itReturnsSortedStreamUsingComparator(): void
    {
        $stream = Stream::fromIterable(range(1, 5));

        $this->assertEquals([5, 4, 3, 2, 1], $stream->sorted(Comparators::values()->reversed()));
    }
}
