<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\PHPUnit;

use ArrayIterator;
use Par\Core\PHPUnit\IterableComparator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SebastianBergmann\Comparator\ComparisonFailure;
use SebastianBergmann\Comparator\Factory;

final class IterableComparatorTest extends TestCase
{
    public static function assertEqualityProvider(): iterable
    {
        yield 'expected with different keys' => [
            new ArrayIterator([1 => 1, 2, 3, 4, 5]),
            [1 => 1, 2, 3, 4, 5],
            range(1, 5)
        ];

        yield 'actual with different keys' => [
            new ArrayIterator(range(1, 5)),
            range(1, 5),
            [1 => 1, 2, 3, 4, 5]
        ];

        yield 'actual with more items' => [
            new ArrayIterator(range(1, 5)),
            range(1, 5),
            range(1, 6)
        ];

        yield 'expected with more items' => [
            new ArrayIterator(range(1, 6)),
            range(1, 6),
            range(1, 5)
        ];
    }

    #[Test]
    public function itAcceptsWhenExpectedAndActualAreIterableAndNotBothArray(): void
    {
        $comparator = new IterableComparator();

        $this->assertTrue($comparator->accepts(new ArrayIterator([]), []));
        $this->assertTrue($comparator->accepts(new ArrayIterator([]), new ArrayIterator([])));
        $this->assertTrue($comparator->accepts([], new ArrayIterator([])));
        $this->assertFalse($comparator->accepts([], []));
        $this->assertFalse($comparator->accepts('foo', new ArrayIterator([])));
    }

    #[Test]
    #[DataProvider("assertEqualityProvider")]
    public function itCanAssertEquality(iterable $expected, iterable $equalActual, iterable $nonEqualActual): void
    {
        $comparator = new IterableComparator();
        $comparator->setFactory(new Factory());

        $comparator->assertEquals($expected, $equalActual);

        $this->expectException(ComparisonFailure::class);
        $comparator->assertEquals($expected, $nonEqualActual);
    }
}
