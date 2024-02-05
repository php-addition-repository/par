<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Comparison;

use Par\Core\Comparison\CallableComparator;
use Par\Core\Comparison\Order;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CallableComparatorTest extends TestCase
{
    #[Test]
    public function itCanCompareUsingCallableThatReturnsInt(): void
    {
        $comparator = new CallableComparator(static fn(int $a, int $b): int => $a <=> $b);

        self::assertEquals(Order::Equal, $comparator->compare(1, 1));
        self::assertEquals(Order::Greater, $comparator->compare(2, 1));
        self::assertEquals(Order::Lesser, $comparator->compare(1, 2));
    }

    #[Test]
    public function itCanCompareUsingCallableThatReturnsOrder(): void
    {
        $comparator = new CallableComparator(static fn(int $a, int $b): Order => Order::from($a <=> $b));

        self::assertEquals(Order::Equal, $comparator->compare(1, 1));
        self::assertEquals(Order::Greater, $comparator->compare(2, 1));
        self::assertEquals(Order::Lesser, $comparator->compare(1, 2));
    }
}
