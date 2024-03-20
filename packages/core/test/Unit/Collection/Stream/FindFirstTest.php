<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream\MixedStream;
use Par\Core\Optional;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class FindFirstTest extends TestCase
{
    #[Test]
    public function itCanFindFirst(): void
    {
        self::assertEquals(Optional::fromAny(2), MixedStream::fromIterable(range(2, 5))->findFirst());
        self::assertEquals(Optional::empty(), MixedStream::empty()->findFirst());
    }
}
