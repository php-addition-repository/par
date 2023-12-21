<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\PHPUnit;

use Par\Core\Equable;
use Par\Core\PHPUnit\EquableComparator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\Comparator\ComparisonFailure;

final class EquableComparatorTest extends TestCase
{
    #[Test]
    public function itAcceptsWhenExpectedOrActualIsInstanceOfObjectEqualityInterface(): void
    {
        $comparator = new EquableComparator();

        $objectEqualityMock = $this->createMock(Equable::class);

        $this->assertTrue($comparator->accepts($objectEqualityMock, 'bar'));
        $this->assertTrue($comparator->accepts('bar', $objectEqualityMock));
        $this->assertFalse($comparator->accepts('bar', 'foo'));
    }

    #[Test]
    public function ttCanAssertEquality(): void
    {
        $comparator = new EquableComparator();

        $objectEqualityMock = $this->createMock(Equable::class);
        $objectEqualityMock->method('equals')->willReturnOnConsecutiveCalls(true, true, true, false);

        $comparator->assertEquals($objectEqualityMock, 'foo');
        $comparator->assertEquals(1, $objectEqualityMock);
        $comparator->assertEquals($objectEqualityMock, $objectEqualityMock);

        $this->expectException(ComparisonFailure::class);
        $comparator->assertEquals($objectEqualityMock, 'bar');
    }
}
