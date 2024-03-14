<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream\MixedStream;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AllMatchTest extends TestCase
{
    #[Test]
    public function itReturnsFalseIfOneDoesNotMatch(): void
    {
        $stream = MixedStream::fromIterable(['a', 'b', false, 'd', 'e']);

        self::assertFalse($stream->allMatch(static fn(mixed $value): bool => is_string($value)));
    }

    #[Test]
    public function itReturnsTrueIfAllMatch(): void
    {
        $stream = MixedStream::fromIterable(range('a', 'e'));

        self::assertTrue($stream->allMatch(static fn(mixed $value): bool => is_string($value)));
    }

    #[Test]
    public function itReturnsTrueWithEmptyStream(): void
    {
        $stream = MixedStream::empty();

        self::assertTrue($stream->allMatch(static fn(mixed $value): bool => is_string($value)));
    }
}
