<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream\MixedStream;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class FilterTest extends TestCase
{
    #[Test]
    public function itReturnsFilteredStream(): void
    {
        $stream = MixedStream::fromIterable(range(1, 5));

        self::assertEquals([1, 2], $stream->filter(static fn(int $value): bool => $value < 3));
    }
}
