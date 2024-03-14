<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream\MixedStream;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ToArrayTest extends TestCase
{
    #[Test]
    public function itReturnsAnZeroBasedArray(): void
    {
        $stream = MixedStream::fromIterable(range(1, 5));

        self::assertSame([1, 2, 3, 4, 5], $stream->toArray());
    }
}
