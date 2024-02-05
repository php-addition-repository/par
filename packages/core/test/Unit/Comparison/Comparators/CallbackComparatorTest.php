<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Comparison\Comparators;

use Par\Core\Comparison\Comparators;
use Par\Core\Comparison\Order;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CallbackComparatorTest extends TestCase
{
    public static function comparableValuesProvider(): iterable
    {
        yield 'equal' => [1, 1, Order::Equal];
        yield 'greater' => ['a10', 'a1', Order::Greater];
        yield 'lesser' => [0.1, 2, Order::Lesser];
    }

    #[Test]
    #[DataProvider('comparableValuesProvider')]
    public function itCanCompareValues(mixed $a, mixed $b, Order $expected): void
    {
        $comparator = Comparators::with(static fn(mixed $a, mixed $b): int => $a <=> $b);

        self::assertEquals($expected, $comparator->compare($a, $b));
    }
}
