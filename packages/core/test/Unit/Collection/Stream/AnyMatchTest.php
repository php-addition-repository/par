<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AnyMatchTest extends TestCase
{
    #[Test]
    public function itReturnsTrueIfOneMatches(): void
    {
        $stream = Stream::fromIterable(['a', 'b', false, 'd', 'e']);

        $this->assertTrue($stream->anyMatch(static fn(mixed $value): bool => is_bool($value)));
    }

    #[Test]
    public function itReturnsFalseIfNoneMatch(): void
    {
        /** @var Stream<mixed> $stream */
        $stream = Stream::fromIterable(range('a', 'e'));

        $this->assertFalse($stream->anyMatch(static fn(mixed $value): bool => !is_string($value)));
    }

    #[Test]
    public function itReturnsFalseWithEmptyStream(): void
    {
        /** @var Stream<mixed> $stream */
        $stream = Stream::empty();

        $this->assertFalse($stream->anyMatch(static fn(mixed $value): bool => is_string($value)));
    }
}
