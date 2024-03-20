<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArrayMap;

use BadFunctionCallException;
use Par\Core\Collection\ArrayMap;
use Par\Core\Optional;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ComputeTest extends TestCase
{
    #[Test]
    public function itWillPutKeyIfAbsentAndComputedNotNull(): void
    {
        $map = ArrayMap::fromArray(['a' => 1, 'b' => 2, 'c' => null]);
        self::assertEquals(
            Optional::empty(),
            $map->computeIfAbsent('b', static fn() => throw new BadFunctionCallException())
        );
        self::assertEquals(
            Optional::empty(),
            $map->computeIfAbsent('d', static fn(): ?int => null)
        );
        self::assertEquals(
            Optional::fromAny(10),
            $map->computeIfAbsent('z', static fn(): int => 10)
        );

        self::assertEquals(['a' => 1, 'b' => 2, 'c' => null, 'z' => 10], $map);
    }

    #[Test]
    public function itWillPutKeyIfComputedNotNull(): void
    {
        $map = ArrayMap::fromArray(['a' => 1, 'b' => 2, 'c' => null]);

        $remappingFunction = static fn(string $key, ?int $value): int => is_int($value) ? $value + 1 : 1;
        self::assertEquals(
            Optional::fromAny(3),
            $map->compute('b', $remappingFunction)
        );
        self::assertEquals(
            Optional::fromAny(1),
            $map->compute('z', $remappingFunction)
        );
        self::assertEquals(['a' => 1, 'b' => 3, 'c' => null, 'z' => 1], $map);
    }

    #[Test]
    public function itWillPutKeyIfPresentAndComputedNotNull(): void
    {
        $map = ArrayMap::fromArray(['a' => 1, 'b' => 2, 'c' => null]);

        self::assertEquals(
            Optional::fromAny(3),
            $map->computeIfPresent('b', static fn() => 3)
        );
        self::assertEquals(
            Optional::empty(),
            $map->computeIfPresent('z', static fn() => throw new BadFunctionCallException())
        );

        self::assertEquals(['a' => 1, 'b' => 3, 'c' => null], $map);
    }

    #[Test]
    public function itWillRemoveKeyIfPresentAndComputedNull(): void
    {
        $map = ArrayMap::fromArray(['a' => 1, 'b' => 2, 'c' => null]);

        $remappingFunction = static fn(string $key, ?int $value): ?int => null;
        self::assertEquals(
            Optional::empty(),
            $map->computeIfPresent('b', $remappingFunction)
        );
        self::assertEquals(['a' => 1, 'c' => null], $map);
    }

    #[Test]
    public function itWillRemoveKeyIfComputedNull(): void
    {
        $map = ArrayMap::fromArray(['a' => 1, 'b' => 2, 'c' => null]);

        $remappingFunction = static fn(string $key, ?int $value): ?int => null;
        self::assertEquals(
            Optional::empty(),
            $map->compute('b', $remappingFunction)
        );
        self::assertEquals(
            Optional::empty(),
            $map->compute('z', $remappingFunction)
        );
        self::assertEquals(['a' => 1, 'c' => null], $map);
    }
}
