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
final class CountTest extends TestCase
{
    public static function provideForCounting(): iterable
    {
        yield 'not-empty' => [ArrayMap::fromArray(['foo' => 1, 'bar' => 2, 'baz' => 3]), 3];
        yield 'empty' => [ArrayMap::empty(), 0];
    }

    #[Test]
    #[DataProvider('provideForCounting')]
    public function itCanBeCounted(ArrayMap $sequence, int $expectedCount): void
    {
        self::assertCount($expectedCount, $sequence);
    }
}
