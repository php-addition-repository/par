<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArrayMap;

use Par\Core\Collection\ArrayMap;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ArrayAccessTest extends TestCase
{
    #[Test]
    public function itSupportsArrayOffsetGet(): void
    {
        $map = ArrayMap::fromArray(['a' => 1, 'b' => 2, 'c' => 3]);

        self::assertEquals(2, $map['b']);
    }

    #[Test]
    public function itSupportsArrayOffsetSet(): void
    {
        $map = ArrayMap::fromArray(['a' => 1, 'b' => 2, 'c' => 3]);

        $map['b'] = 4;
        $map['d'] = 5;

        self::assertEquals(['a' => 1, 'b' => 4, 'c' => 3, 'd' => 5], $map);
    }

    #[Test]
    public function itSupportsOffsetIsset(): void
    {
        $map = ArrayMap::fromArray(['a' => 1, 'b' => 2, 'c' => 3]);

        self::assertTrue(isset($map['a']));
        self::assertFalse(isset($map['z']));
    }

    #[Test]
    public function itSupportsOffsetUnset(): void
    {
        $map = ArrayMap::fromArray(['a' => 1, 'b' => 2, 'c' => 3]);

        unset($map['a']);
        unset($map['z']);

        self::assertEquals(['b' => 2, 'c' => 3], $map);
    }
}
