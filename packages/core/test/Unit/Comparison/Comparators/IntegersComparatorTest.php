<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Comparison\Comparators;

use Par\Core\Comparison\Comparators;
use Par\Core\Comparison\Exception\IncomparableException;
use Par\Core\Comparison\Order;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class IntegersComparatorTest extends TestCase
{
    public static function comparableValuesProvider(): iterable
    {
        yield 'equal' => [1, 1, Order::Equal];
        yield 'greater' => [2, 1, Order::Greater];
        yield 'lesser' => [2, 3, Order::Lesser];
    }

    public static function comparableValuesUsingExtractorProvider(): iterable
    {
        $toIntExtractor = static fn(string $value): int => (int)$value;

        yield 'equal' => ['1', '1', $toIntExtractor, Order::Equal];
        yield 'greater' => ['2', '1', $toIntExtractor, Order::Greater];
        yield 'lesser' => ['2', '3', $toIntExtractor, Order::Lesser];
    }

    public static function incompatibleValuesProvider(): iterable
    {
        yield 'strings' => ['foo', 'bar'];
        yield 'floats' => [1.0, 2.0];
        yield 'a-not-compatible' => ['foo', 1];
        yield 'b-not-compatible' => [1, 'foo'];
    }

    #[Test]
    #[DataProvider("incompatibleValuesProvider")]
    public function itThrowsIncomparableExceptionForIncompatibleValues(mixed $a, mixed $b): void
    {
        $comparator = Comparators::integers();

        $this->expectException(IncomparableException::class);
        $comparator->compare($a, $b);
    }

    /**
     * @param pure-callable(mixed): int $extractor
     */
    #[Test]
    #[DataProvider("comparableValuesUsingExtractorProvider")]
    public function itWillCompareValuesFromExtractor(mixed $a, mixed $b, callable $extractor, Order $expected): void
    {
        $comparator = Comparators::integers($extractor);

        $this->assertEquals($expected, $comparator->compare($a, $b));
    }

    #[Test]
    #[DataProvider("comparableValuesProvider")]
    public function itCanCompareValues(int $a, int $b, Order $expected): void
    {
        $comparator = Comparators::integers();

        $this->assertEquals($expected, $comparator->compare($a, $b));
    }
}
