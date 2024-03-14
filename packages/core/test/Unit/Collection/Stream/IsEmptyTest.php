<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream\MixedStream;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class IsEmptyTest extends TestCase
{
    #[Test]
    public function itReturnsTrueForEmptyStream(): void
    {
        $stream = MixedStream::empty();

        self::assertTrue($stream->isEmpty());
    }

    #[Test]
    public function itReturnsFalseForStreamWithElementsWithoutIteratingAll(): void
    {
        $peeked = 0;

        $stream = MixedStream::fromIterable(range(1, 5))
                             ->peek(
                                 static function() use (&$peeked): void {
                                     ++$peeked;
                                 }
                             );

        self::assertFalse($stream->isEmpty());
        self::assertEquals(1, $peeked);
    }
}
