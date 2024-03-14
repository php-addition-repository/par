<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Comparison\Comparators;

use Par\Core\Comparison\Comparators;
use Par\Core\Comparison\Exception\IncomparableException;
use Par\Core\Comparison\Order;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class FloatsComparatorTest extends TestCase
{
    public static function comparableValuesProvider(): iterable
    {
        yield 'equal' => [1, 1, Order::Equal];
        yield 'greater' => [2, 1, Order::Greater];
        yield 'lesser' => [2, 3, Order::Lesser];
    }

    public static function comparableValuesUsingExtractorProvider(): iterable
    {
        $toFloatExtractor = static fn(string|float|int $value): float => (float) $value;

        yield 'equal' => ['0.1', 0.1, $toFloatExtractor, Order::Equal];
        yield 'greater' => ['2', 1, $toFloatExtractor, Order::Greater];
        yield 'lesser' => ['0.2', '3', $toFloatExtractor, Order::Lesser];
    }

    public static function incompatibleValuesProvider(): iterable
    {
        yield 'strings' => ['foo', 'bar'];
        yield 'integers' => [1, 2];
        yield 'a-not-compatible' => ['foo', 1.0];
        yield 'b-not-compatible' => [1.0, 'foo'];
    }

    #[Test]
    #[DataProvider('incompatibleValuesProvider')]
    public function itThrowsIncomparableExceptionForIncompatibleValues(mixed $a, mixed $b): void
    {
        $comparator = Comparators::floats();

        $this->expectException(IncomparableException::class);
        $comparator->compare($a, $b);
    }

    /**
     * @param pure-callable(mixed): float $extractor
     */
    #[Test]
    #[DataProvider('comparableValuesUsingExtractorProvider')]
    public function itWillCompareValuesFromExtractor(mixed $a, mixed $b, callable $extractor, Order $expected): void
    {
        $comparator = Comparators::floats($extractor);

        self::assertEquals($expected, $comparator->compare($a, $b));
    }

    #[Test]
    #[DataProvider('comparableValuesProvider')]
    public function itCanCompareValues(float $a, float $b, Order $expected): void
    {
        $comparator = Comparators::floats();

        self::assertEquals($expected, $comparator->compare($a, $b));
    }
}
