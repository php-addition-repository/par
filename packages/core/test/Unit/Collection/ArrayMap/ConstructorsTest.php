<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArrayMap;

use Par\Core\Collection\ArrayMap;
use Par\Core\Exception\InvalidTypeException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ConstructorsTest extends TestCase
{
    public static function fromArrayProvider(): iterable
    {
        $map = array_combine(range(3, 7), range('a', 'e'));
        yield 'array<int, string>' => [$map, $map];

        $map = array_flip($map);
        yield 'array<string, int>' => [$map, $map];

        $map = [3 => 'a', 'b' => 'b'];
        yield 'array<int|string, string>' => [$map, $map];
    }

    public static function fromIterableProvider(): iterable
    {
        $generator = static fn(iterable $map): iterable => yield from $map;

        $map = array_combine(range(3, 7), range('a', 'e'));
        yield 'iterable<int, string>' => [$generator($map), $map];

        $map = array_flip($map);
        yield 'iterable<string, int>' => [$generator($map), $map];

        $map = [3 => 'a', 'b' => 'b'];
        yield 'iterable<int|string, string>' => [$generator($map), $map];
    }

    #[Test]
    public function cannotCreateFromIterableWithInvalidArrayKeyTypes(): void
    {
        $generator = static function(): iterable {
            yield 1 => null;
            yield 'two' => 2;
            yield null => 3;
        };

        $this->expectExceptionObject(InvalidTypeException::forIndexedValue(2, null, 'int|string'));
        ArrayMap::fromIterable($generator());
    }

    #[Test]
    public function empty(): void
    {
        self::assertEquals(
            [],
            ArrayMap::empty()
        );
    }

    #[Test]
    #[DataProvider('fromArrayProvider')]
    public function fromArray(array $array, array $expectedArray): void
    {
        self::assertEquals(
            $expectedArray,
            ArrayMap::fromArray($array)
        );
    }

    /**
     * @param iterable<array-key, mixed> $array
     */
    #[Test]
    #[DataProvider('fromArrayProvider')]
    #[DataProvider('fromIterableProvider')]
    public function fromIterable(iterable $array, iterable $expectedArray): void
    {
        self::assertEquals(
            $expectedArray,
            ArrayMap::fromIterable($array)
        );
    }
}
