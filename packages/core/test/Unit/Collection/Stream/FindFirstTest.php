<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream;
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
        self::assertEquals(Optional::fromAny(1), Stream::fromIterable(range(1, 5))->findFirst());
        self::assertEquals(Optional::empty(), Stream::empty()->findFirst());
    }
}
