<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArrayMap;

use Par\Core\Collection\ArrayMap;
use Par\Core\Exception\NoSuchElementException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class GetTest extends TestCase
{
    #[Test]
    public function itCanGetTheValueOfElementWithKey(): void
    {
        $map = ArrayMap::fromArray(['foo' => 1, 'bar' => 2, 'baz' => 3]);

        self::assertEquals(2, $map->get('bar'));
    }

    #[Test]
    public function itWillThrowExceptionWhenGettingValueOfNoSuchElement(): void
    {
        $map = ArrayMap::fromArray(['foo' => 1, 'bar' => 2, 'baz' => 3]);

        $this->expectException(NoSuchElementException::class);
        $map->get('foobar');
    }

    #[Test]
    public function itCanGetTheValueOfElementWithKeyOrReturnDefault(): void
    {
        $map = ArrayMap::fromArray(['foo' => 1, 'bar' => 2, 'baz' => 3]);

        self::assertEquals(2, $map->getOrDefault('bar'));
        self::assertEquals(10, $map->getOrDefault('foobar', 10));
    }
}
