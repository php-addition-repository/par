<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArrayMap;

use Par\Core\Collection\ArrayMap;
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
        $stream = ArrayMap::empty();

        self::assertTrue($stream->isEmpty());
    }

    #[Test]
    public function itReturnsFalseForStreamWithElements(): void
    {
        $stream = ArrayMap::fromIterable(['foo' => 1, 'bar' => 2, 'baz' => 3]);

        self::assertFalse($stream->isEmpty());
    }
}
