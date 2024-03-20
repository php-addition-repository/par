<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArrayMap;

use Par\Core\Collection\ArrayMap;
use Par\Core\Collection\DummySet;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class KeySetTest extends TestCase
{
    #[Test]
    public function itCanReturnSetOfKeys(): void
    {
        $map = ArrayMap::fromArray(['foo' => 2, 'bar' => 3, 'baz' => 3]);

        $set = $map->keySet();
        if ($set instanceof DummySet) {
            self::markTestSkipped('ArrayMap::keySet() is returns a dummy set.');
        }

        self::assertEquals(['foo', 'bar', 'baz'], $set);

        $map->put('foobar', 4);
        self::assertEquals(['foo', 'bar', 'baz'], $set);
    }
}
