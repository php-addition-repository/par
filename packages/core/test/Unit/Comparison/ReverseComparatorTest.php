<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Comparison;

use Par\Core\Comparison\CallableComparator;
use Par\Core\Comparison\Order;
use Par\Core\Comparison\ReverseComparator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ReverseComparatorTest extends TestCase
{
    #[Test]
    public function itReturnsDecoratedWhenReversed(): void
    {
        $decorated = new CallableComparator(static fn(int $a, int $b): int => $a <=> $b);
        $comparator = new ReverseComparator($decorated);

        self::assertSame($decorated, $comparator->reversed());
    }

    #[Test]
    public function itReversesOrderOfDecoratedComparator(): void
    {
        $comparator = new ReverseComparator(new CallableComparator(static fn(int $a, int $b): int => $a <=> $b));

        self::assertEquals(Order::Equal, $comparator->compare(1, 1));
        self::assertEquals(Order::Lesser, $comparator->compare(2, 1));
        self::assertEquals(Order::Greater, $comparator->compare(1, 2));
    }
}
