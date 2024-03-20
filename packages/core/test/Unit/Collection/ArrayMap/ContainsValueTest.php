<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArrayMap;

use Par\Core\Collection\ArrayMap;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ContainsValueTest extends TestCase
{
    #[Test]
    public function itWillReturnWhetherMapContainsValue(): void
    {
        $map = ArrayMap::fromArray(['a' => 1, 'b' => 2, 'c' => 3]);

        self::assertTrue($map->containsValue(2));
        self::assertFalse($map->containsValue(5));
    }
}
