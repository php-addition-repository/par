<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream;
use Par\Core\Comparison\Comparators;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class MinMaxTest extends TestCase
{
    #[Test]
    public function itReturnsOptionalUsingNativeComparatorByDefault(): void
    {
        $stream = Stream::fromIterable([5, 4, 3, 2, 1]);

        $this->assertEquals(5, $stream->max()->get());
        $this->assertEquals(1, $stream->min()->get());
    }

    #[Test]
    public function itReturnsOptionalUsingComparator(): void
    {
        $stream = Stream::fromIterable([5, 4, 3, 2, 1]);

        $this->assertEquals(5, $stream->max(Comparators::values())->get());
        $this->assertEquals(1, $stream->min(Comparators::values())->get());
    }

    #[Test]
    public function itReturnsOptionalUsingCallable(): void
    {
        $stream = Stream::fromIterable([5, 4, 3, 2, 1]);

        $callable = static fn(int $a, int $b): int => $a <=> $b;
        $this->assertEquals(5, $stream->max($callable)->get());
        $this->assertEquals(1, $stream->min($callable)->get());
    }

    #[Test]
    public function itReturnsEmptyOptionalForEmptyStream(): void
    {
        $stream = Stream::empty();

        $this->assertTrue($stream->max()->isEmpty());
        $this->assertTrue($stream->min()->isEmpty());
    }
}
