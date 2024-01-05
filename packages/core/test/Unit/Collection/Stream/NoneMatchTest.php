<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class NoneMatchTest extends TestCase
{
    #[Test]
    public function itReturnsTrueIfNoneMatch(): void
    {
        /** @var Stream<mixed> $stream */
        $stream = Stream::fromIterable(['a', 'b', false, 'd', 'e']);

        $this->assertTrue($stream->noneMatch(static fn(mixed $value): bool => $value === 'f'));
    }

    #[Test]
    public function itReturnsFalseIfOneMatch(): void
    {
        /** @var Stream<mixed> $stream */
        $stream = Stream::fromIterable(range('a', 'e'));

        $this->assertFalse($stream->noneMatch(static fn(mixed $value): bool => $value === 'd'));
    }

    #[Test]
    public function itReturnsTrueWithEmptyStream(): void
    {
        /** @var Stream<mixed> $stream */
        $stream = Stream::empty();

        $this->assertTrue($stream->noneMatch(static fn(mixed $value): bool => is_string($value)));
    }
}
