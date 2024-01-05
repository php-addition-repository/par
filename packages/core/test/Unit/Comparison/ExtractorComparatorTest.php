<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Comparison;

use Par\Core\Comparison\CallableComparator;
use Par\Core\Comparison\ExtractorComparator;
use Par\Core\Comparison\Order;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ExtractorComparatorTest extends TestCase
{
    #[Test]
    public function itPassesExtractedValueToDecoratedComparator(): void
    {
        $decorated = new CallableComparator(static fn(int $a, int $b): int => $a <=> $b);
        $comparator = new ExtractorComparator(static fn(string $value): int => (int)$value, $decorated);

        $this->assertEquals(Order::Equal, $comparator->compare('1', '1'));
        $this->assertEquals(Order::Greater, $comparator->compare('2', '1'));
        $this->assertEquals(Order::Lesser, $comparator->compare('1', '2'));
    }
}
