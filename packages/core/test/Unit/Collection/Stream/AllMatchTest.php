<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AllMatchTest extends TestCase
{
    #[Test]
    public function itReturnsFalseIfOneDoesNotMatch(): void
    {
        $stream = Stream::fromIterable(['a', 'b', false, 'd', 'e']);

        $this->assertFalse($stream->allMatch(static fn(mixed $value): bool => is_string($value)));
    }

    #[Test]
    public function itReturnsTrueIfAllMatch(): void
    {
        /** @var Stream<mixed> $stream */
        $stream = Stream::fromIterable(range('a', 'e'));

        $this->assertTrue($stream->allMatch(static fn(mixed $value): bool => is_string($value)));
    }

    #[Test]
    public function itReturnsTrueWithEmptyStream(): void
    {
        /** @var Stream<mixed> $stream */
        $stream = Stream::empty();

        $this->assertTrue($stream->allMatch(static fn(mixed $value): bool => is_string($value)));
    }
}
