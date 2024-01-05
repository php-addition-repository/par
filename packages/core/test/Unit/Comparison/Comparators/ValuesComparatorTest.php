<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Comparison\Comparators;

use Par\Core\Comparison\Comparators;
use Par\Core\Comparison\Order;
use Par\CoreTest\Fixtures\ComparableScalarObject;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ValuesComparatorTest extends TestCase
{
    public static function comparableValuesProvider(): iterable
    {
        yield 'equal-objects' => [new ComparableScalarObject(1), new ComparableScalarObject(1), Order::Equal];
        yield 'equal-ints' => [1, 1, Order::Equal];
        yield 'greater-objects' => [new ComparableScalarObject(2), new ComparableScalarObject(1), Order::Greater];
        yield 'greater-ints' => [2, 1, Order::Greater];
        yield 'lesser-objects' => [new ComparableScalarObject(1), new ComparableScalarObject(2), Order::Lesser];
        yield 'lesser-ints' => [1, 2, Order::Lesser];
    }

    #[Test]
    #[DataProvider("comparableValuesProvider")]
    public function itCanCompareValues(mixed $a, mixed $b, Order $expected): void
    {
        $comparator = Comparators::values();

        $this->assertEquals($expected, $comparator->compare($a, $b));
    }
}
