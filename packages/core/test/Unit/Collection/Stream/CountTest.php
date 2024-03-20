<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream\MixedStream;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CountTest extends TestCase
{
    #[Test]
    public function itReturnsNumberOfElementsInStream(): void
    {
        $stream = MixedStream::fromIterable(range(1, 5));

        self::assertCount(5, $stream);
    }

    #[Test]
    public function itReturnsZeroForEmptyStream(): void
    {
        $stream = MixedStream::empty();
        self::assertCount(0, $stream);
    }
}
