<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream;
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
        $stream = Stream::fromIterable(range(1, 5));

        self::assertCount(5, $stream);
    }

    #[Test]
    public function itReturnsZeroForEmptyStream(): void
    {
        $stream = Stream::empty();
        self::assertCount(0, $stream);
    }
}
