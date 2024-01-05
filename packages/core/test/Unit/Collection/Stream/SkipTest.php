<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream;
use Par\Core\Exception\AssertionFailedException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class SkipTest extends TestCase
{
    #[Test]
    public function itDoesNotAllowNegativeNumber(): void
    {
        $stream = Stream::fromIterable(range(1, 5));

        $this->expectException(AssertionFailedException::class);
        /** @psalm-suppress InvalidArgument */
        $stream->skip(-2);
    }

    #[Test]
    public function itReturnsSameStreamWhenSkippingZero(): void
    {
        $stream = Stream::fromIterable(range(1, 5));

        $this->assertEquals($stream, $stream->skip(0));
    }

    #[Test]
    public function itSkipsElements(): void
    {
        $stream = Stream::fromIterable(range(1, 5));

        $this->assertEquals([3, 4, 5], $stream->skip(2));
    }
}
