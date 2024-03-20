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
final class RemoveTest extends TestCase
{
    #[Test]
    public function itCanRemoveExistingMapping(): void
    {
        $map = ArrayMap::fromArray(['foo' => 1, 'bar' => 2, 'baz' => 3]);

        self::assertEquals(Optional::fromAny(2), $map->remove('bar'));
        self::assertEquals(['foo' => 1, 'baz' => 3], $map);
    }

    #[Test]
    public function itCanRemoveNoneExistingMapping(): void
    {
        $mapping = ['foo' => 1, 'bar' => 2, 'baz' => 3];
        $map = ArrayMap::fromArray($mapping);

        self::assertEquals(Optional::empty(), $map->remove('foobar'));
        self::assertEquals($mapping, $map);
    }

    #[Test]
    public function itDoesNotAcceptInvalidTypeForKey(): void
    {
        $mapping = ['foo' => 1, 'bar' => 2, 'baz' => 3];
        $map = ArrayMap::fromArray($mapping);

        $this->expectException(InvalidTypeException::class);
        /* @phpstan-ignore-next-line */
        $map->remove(null);
    }
}
