<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArrayMap;

use Par\Core\Collection\ArrayMap;
use Par\Core\Exception\InvalidTypeException;
use Par\Core\Optional;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class PutTest extends TestCase
{
    #[Test]
    public function itCanPutElementMapping(): void
    {
        $map = ArrayMap::fromArray(['foo' => 1, 'bar' => 2, 'baz' => 3]);

        self::assertEquals(Optional::fromAny(2), $map->put('bar', 4));
        self::assertEquals(Optional::empty(), $map->put('foobar', 4));
        self::assertEquals(['foo' => 1, 'bar' => 4, 'baz' => 3, 'foobar' => 4], $map);
    }

    #[Test]
    public function itCanPutElementMappingIfAbsent(): void
    {
        $map = ArrayMap::fromArray(['foo' => 1, 'bar' => 2, 'baz' => 3]);

        self::assertEquals(Optional::fromAny(2), $map->putIfAbsent('bar', 4));
        self::assertEquals(Optional::empty(), $map->putIfAbsent('foobar', 4));
        self::assertEquals(['foo' => 1, 'bar' => 2, 'baz' => 3, 'foobar' => 4], $map);
    }

    #[Test]
    public function itDoesNotAcceptInvalidTypeForKey(): void
    {
        $mapping = ['foo' => 1, 'bar' => 2, 'baz' => 3];
        $map = ArrayMap::fromArray($mapping);

        $this->expectException(InvalidTypeException::class);
        /* @phpstan-ignore-next-line */
        $map->put(null, null);
    }

    #[Test]
    public function itCanPutAllElementsFromOtherMap(): void
    {
        $mapping = ['foo' => 1, 'bar' => 2, 'baz' => 3];
        $otherMap = ArrayMap::fromArray($mapping);

        $map = ArrayMap::fromArray(['foo' => 2]);
        $map->putAll($otherMap);

        self::assertEquals(['foo' => 1, 'bar' => 2, 'baz' => 3], $map);
    }
}
