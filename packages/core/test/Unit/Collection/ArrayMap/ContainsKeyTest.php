<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArrayMap;

use Par\Core\Collection\ArrayMap;
use Par\Core\Exception\InvalidTypeException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ContainsKeyTest extends TestCase
{
    #[Test]
    public function itWillReturnWhetherMapContainsKey(): void
    {
        $map = ArrayMap::fromArray(['a' => 1, 'b' => 2, 'c' => 3]);

        self::assertTrue($map->containsKey('b'));
        self::assertFalse($map->containsKey('z'));
    }

    #[Test]
    public function itWillThrowInvalidTypeExceptionWhenKeyNotArrayKey(): void
    {
        $map = ArrayMap::fromArray(['a' => 1, 'b' => 2, 'c' => 3]);

        $this->expectException(InvalidTypeException::class);
        /* @phpstan-ignore-next-line */
        $map->containsKey(null);
    }
}
