<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Comparison;

use Par\Core\Comparison\CallableComparator;
use Par\Core\Comparison\Order;
use Par\Core\Comparison\ReverseComparator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ReverseComparatorTest extends TestCase
{
    #[Test]
    public function itReturnsDecoratedWhenReversed(): void
    {
        $decorated = new CallableComparator(static fn(int $a, int $b): int => $a <=> $b);
        $comparator = new ReverseComparator($decorated);

        $this->assertSame($decorated, $comparator->reversed());
    }

    #[Test]
    public function itReversesOrderOfDecoratedComparator(): void
    {
        $comparator = new ReverseComparator(new CallableComparator(static fn(int $a, int $b): int => $a <=> $b));

        $this->assertEquals(Order::Equal, $comparator->compare(1, 1));
        $this->assertEquals(Order::Lesser, $comparator->compare(2, 1));
        $this->assertEquals(Order::Greater, $comparator->compare(1, 2));
    }
}
