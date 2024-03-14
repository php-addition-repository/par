<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream\MixedStream;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class NoneMatchTest extends TestCase
{
    #[Test]
    public function itReturnsTrueIfNoneMatch(): void
    {
        $stream = MixedStream::fromIterable(['a', 'b', false, 'd', 'e']);

        self::assertTrue($stream->noneMatch(static fn(mixed $value): bool => 'f' === $value));
    }

    #[Test]
    public function itReturnsFalseIfOneMatch(): void
    {
        $stream = MixedStream::fromIterable(range('a', 'e'));

        self::assertFalse($stream->noneMatch(static fn(mixed $value): bool => 'd' === $value));
    }

    #[Test]
    public function itReturnsTrueWithEmptyStream(): void
    {
        $stream = MixedStream::empty();

        self::assertTrue($stream->noneMatch(static fn(mixed $value): bool => is_string($value)));
    }
}
