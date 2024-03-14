<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\PHPUnit;

use Par\Core\Equable;
use Par\Core\PHPUnit\EquableComparator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\Comparator\ComparisonFailure;

/**
 * @internal
 */
final class EquableComparatorTest extends TestCase
{
    #[Test]
    public function itAcceptsWhenExpectedOrActualIsInstanceOfObjectEqualityInterface(): void
    {
        $comparator = new EquableComparator();

        $objectEqualityMock = $this->createMock(Equable::class);

        self::assertTrue($comparator->accepts($objectEqualityMock, $objectEqualityMock));
        self::assertTrue($comparator->accepts($objectEqualityMock, null));
        self::assertFalse($comparator->accepts($objectEqualityMock, 'bar'));
        self::assertFalse($comparator->accepts('bar', $objectEqualityMock));
    }

    #[Test]
    public function itCanAssertEquality(): void
    {
        $comparator = new EquableComparator();

        $objectEqualityMock = $this->createMock(Equable::class);
        $objectEqualityMock->method('equals')->willReturnOnConsecutiveCalls(true, false);

        $comparator->assertEquals($objectEqualityMock, $objectEqualityMock);

        $this->expectException(ComparisonFailure::class);
        $comparator->assertEquals($objectEqualityMock, null);
    }
}
