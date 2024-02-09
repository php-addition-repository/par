<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ReduceTest extends TestCase
{
    #[Test]
    public function itCanReduceElements(): void
    {
        $stream = Stream::fromIterable(range(1, 5));

        self::assertEquals(
            15,
            $stream->reduce(0, static fn(int $carry, int $element): int => $carry + $element)
        );
    }

    #[Test]
    public function itWillReturnCarryWhenStreamIsEmpty(): void
    {
        $stream = Stream::empty();

        $carry = 0;
        self::assertEquals(
            $carry,
            $stream->reduce($carry, static fn(int $carry, int $element): int => $carry + $element)
        );
    }
}
