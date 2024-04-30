<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream\MixedStream;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AnyMatchTest extends TestCase
{
    #[Test]
    public function itReturnsTrueIfOneMatches(): void
    {
        $stream = MixedStream::fromIterable(['a', 'b', false, 'd', 'e']);

        self::assertTrue($stream->anyMatch(static fn(mixed $value): bool => is_bool($value)));
    }

    #[Test]
    public function itReturnsFalseIfNoneMatch(): void
    {
        $stream = MixedStream::fromIterable(range('a', 'e'));

        self::assertFalse($stream->anyMatch(static fn(mixed $value): bool => !is_string($value)));
    }

    #[Test]
    public function itReturnsFalseWithEmptyStream(): void
    {
        $stream = MixedStream::empty();

        self::assertFalse($stream->anyMatch(static fn(mixed $value): bool => is_string($value)));
    }
}
