<?php

declare(strict_types=1);

namespace ParTest\Core\Unit\Comparison;

use Par\Core\Comparison\Comparator;
use Par\Core\Comparison\Exception\IncomparableException;
use Par\Core\Comparison\GuardComparator;
use Par\Core\Comparison\Order;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class GuardComparatorTest extends TestCase
{
    public function testItExecutesPredicateOnBothValuesBeforeExecutingDecoratedComparator(): void
    {
        $a = 'a';
        $b = 'b';
        $decorated = $this->createMock(Comparator::class);
        $decorated->expects(self::once())->method('compare')->with($a, $b)->willReturn(Order::Lesser);

        $tested = [];

        $comparator = new GuardComparator(
            $decorated,
            static function(string $value) use (&$tested): bool {
                $tested[] = $value;

                return 'a' === $value || 'b' === $value;
            },
        );

        self::assertEquals(Order::Lesser, $comparator->compare($a, $b));
        self::assertEquals(['a', 'b'], $tested);
    }

    public function testItThrowsIncomparableExceptionWhenPredicateIsFalse(): void
    {
        $a = 'a';
        $b = 'b';
        $additionalInfo = 'Both values must be an "a" or "b".';

        $decorated = $this->createMock(Comparator::class);
        $decorated->expects(self::never())->method('compare');

        $comparator = new GuardComparator(
            $decorated,
            static function(string $value): bool {
                return 'a' !== $value && 'b' !== $value;
            },
            $additionalInfo,
        );

        $this->expectExceptionObject(IncomparableException::fromValues($a, $b, $additionalInfo));
        $comparator->compare($a, $b);
    }
}
