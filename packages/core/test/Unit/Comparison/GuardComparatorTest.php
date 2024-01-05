<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Comparison;

use Par\Core\Comparison\Comparator;
use Par\Core\Comparison\Exception\IncomparableException;
use Par\Core\Comparison\GuardComparator;
use Par\Core\Comparison\Order;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class GuardComparatorTest extends TestCase
{
    #[Test]
    public function itExecutesTestOnBothValues(): void
    {
        $a = 'a';
        $b = 'b';
        $decorated = $this->createMock(Comparator::class);
        $decorated->expects($this->once())->method('compare')->with($a, $b)->willReturn(Order::Lesser);

        $tested = [];

        $comparator = new GuardComparator(
            $decorated,
            static function (string $value) use (&$tested): bool {
                $tested[] = $value;

                return $value === 'a' || $value === 'b';
            }
        );

        $this->assertEquals(Order::Lesser, $comparator->compare($a, $b));
        $this->assertEquals(['a', 'b'], $tested);
    }

    #[Test]
    public function itReturnsResultFromDecoratedWhenTestReturnsTrue(): void
    {
        $a = 'a';
        $b = 'b';
        $decorated = $this->createMock(Comparator::class);
        $decorated->expects($this->once())->method('compare')->with($a, $b)->willReturn(Order::Lesser);

        $comparator = new GuardComparator(
            $decorated,
            static function (string $value): bool {
                return $value === 'a' || $value === 'b';
            }
        );

        $this->assertEquals(Order::Lesser, $comparator->compare($a, $b));
    }

    #[Test]
    public function itThrowsIncomparableExceptionWhenTestReturnsFalse(): void
    {
        $a = 'a';
        $b = 'b';
        $additionalInfo = 'test is success';

        $decorated = $this->createMock(Comparator::class);
        $decorated->expects($this->never())->method('compare');

        $comparator = new GuardComparator(
            $decorated,
            static function (string $value): bool {
                return $value !== 'a' && $value !== 'b';
            },
            $additionalInfo
        );

        $this->expectExceptionObject(IncomparableException::withIncompatibleTypes($a, $b, $additionalInfo));
        $comparator->compare($a, $b);
    }
}
