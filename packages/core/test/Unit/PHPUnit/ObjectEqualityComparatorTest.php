<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\PHPUnit;

use Par\Core\ObjectEquality;
use Par\Core\PHPUnit\ObjectEqualityComparator;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\Comparator\ComparisonFailure;

final class ObjectEqualityComparatorTest extends TestCase
{
    #[Test]
    public function itAcceptsWhenExpectedOrActualIsInstanceOfObjectEqualityInterface(): void
    {
        $comparator = new ObjectEqualityComparator();

        $objectEqualityMock = $this->createMock(ObjectEquality::class);

        $this->assertTrue($comparator->accepts($objectEqualityMock, 'bar'));
        $this->assertTrue($comparator->accepts('bar', $objectEqualityMock));
        $this->assertFalse($comparator->accepts('bar', 'foo'));
    }

    #[Test]
    public function ttCanAssertEquality(): void
    {
        $comparator = new ObjectEqualityComparator();

        $objectEqualityMock = $this->createMock(ObjectEquality::class);
        $objectEqualityMock->method('equals')->willReturnOnConsecutiveCalls(true, true, true, false);

        $comparator->assertEquals($objectEqualityMock, 'foo');
        $comparator->assertEquals('foo', $objectEqualityMock);
        $comparator->assertEquals($objectEqualityMock, $objectEqualityMock);

        $this->expectException(ComparisonFailure::class);
        $comparator->assertEquals($objectEqualityMock, 'bar');
    }
}
