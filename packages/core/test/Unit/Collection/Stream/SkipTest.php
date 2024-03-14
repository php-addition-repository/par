<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream\MixedStream;
use Par\Core\Exception\AssertionFailedException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class SkipTest extends TestCase
{
    #[Test]
    public function itDoesNotAllowNegativeNumber(): void
    {
        $stream = MixedStream::fromIterable(range(1, 5));

        $this->expectException(AssertionFailedException::class);

        /* @phpstan-ignore-next-line */
        $stream->skip(-2);
    }

    #[Test]
    public function itReturnsSameStreamWhenSkippingZero(): void
    {
        $stream = MixedStream::fromIterable(range(1, 5));

        self::assertEquals($stream, $stream->skip(0));
    }

    #[Test]
    public function itSkipsElements(): void
    {
        $stream = MixedStream::fromIterable(range(1, 5));

        self::assertEquals([3, 4, 5], $stream->skip(2));
    }
}
