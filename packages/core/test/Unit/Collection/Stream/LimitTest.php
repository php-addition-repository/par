<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream;
use Par\Core\Exception\AssertionFailedException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class LimitTest extends TestCase
{
    #[Test]
    public function itDoesNotAllowNegativeNumber(): void
    {
        $stream = Stream::fromIterable(range(1, 5));

        $this->expectException(AssertionFailedException::class);
        /** @phpstan-ignore-next-line */
        $stream->limit(-2);
    }

    #[Test]
    public function itReturnsEmptyStreamWhenLimitingToZero(): void
    {
        $stream = Stream::fromIterable(range(1, 5));

        $this->assertEquals([], $stream->limit(0));
    }

    #[Test]
    public function itReturnsLimitedStream(): void
    {
        $stream = Stream::fromIterable(range(1, 5));

        $this->assertEquals([1, 2], $stream->limit(2));
    }
}
