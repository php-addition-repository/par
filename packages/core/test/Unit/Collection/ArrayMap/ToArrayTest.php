<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArrayMap;

use Par\Core\Collection\ArrayMap;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ToArrayTest extends TestCase
{
    public static function provideForArrayTransformation(): iterable
    {
        yield 'empty' => [
            ArrayMap::empty(),
            [],
        ];

        yield 'string[]' => [
            ArrayMap::fromIterable(range('a', 'e')),
            range('a', 'e'),
        ];

        yield 'array<string, mixed>' => [
            ArrayMap::fromArray(['foo' => 1, 'bar' => 2, 'baz' => 3]),
            ['foo' => 1, 'bar' => 2, 'baz' => 3],
        ];
    }

    #[Test]
    #[DataProvider('provideForArrayTransformation')]
    public function itCanBeTransformedToArray(ArrayMap $map, array $expectedArray): void
    {
        self::assertEquals($expectedArray, $map->toArray());
    }
}
