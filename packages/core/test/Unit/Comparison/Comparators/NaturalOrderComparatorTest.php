<?php

declare(strict_types=1);

namespace ParTest\Core\Unit\Comparison\Comparators;

use Par\Core\Comparison\Comparators;
use Par\Core\Comparison\Exception\IncomparableException;
use Par\Core\Comparison\Order;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stringable;

/**
 * @internal
 */
final class NaturalOrderComparatorTest extends TestCase
{
    public static function comparableValuesProvider(): iterable
    {
        yield 'equal' => ['a10', 'A10', Order::Equal];
        yield 'greater' => ['A10', 'a1', Order::Greater];
        yield 'lesser' => [
            'a1',
            new class() implements Stringable {
                public function __toString(): string
                {
                    return 'A10';
                }
            },
            Order::Lesser,
        ];
    }

    public static function incompatibleValuesProvider(): iterable
    {
        yield 'floats' => [0.1, 0.2];
        yield 'integers' => [1, 2];
        yield 'a-not-compatible' => [1, 'foo'];
        yield 'b-not-compatible' => ['foo', 1];
    }

    #[DataProvider('comparableValuesProvider')]
    public function testItCanCompareValues(string|Stringable $a, string|Stringable $b, Order $expected): void
    {
        $comparator = Comparators::naturalOrder();

        self::assertEquals($expected, $comparator->compare($a, $b));
    }

    #[DataProvider('incompatibleValuesProvider')]
    public function testItThrowsIncomparableExceptionForIncompatibleValues(mixed $a, mixed $b): void
    {
        $comparator = Comparators::naturalOrder();

        $this->expectException(IncomparableException::class);
        $comparator->compare($a, $b);
    }
}
