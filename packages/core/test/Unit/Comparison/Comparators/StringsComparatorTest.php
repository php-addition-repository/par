<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Comparison\Comparators;

use Par\Core\Comparison\Comparators;
use Par\Core\Comparison\Exception\IncomparableException;
use Par\Core\Comparison\Order;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Stringable;

final class StringsComparatorTest extends TestCase
{
    public static function comparableValuesProvider(): iterable
    {
        yield 'equal' => ['a10', 'a10', Order::Equal];
        yield 'greater' => ['a10', 'a1', Order::Greater];
        yield 'lesser' => [
            'a1',
            new class () implements Stringable {
                public function __toString(): string
                {
                    return 'a10';
                }
            },
            Order::Lesser
        ];
    }

    public static function comparableValuesUsingExtractorProvider(): iterable
    {
        $toStringExtractor = static fn(float|int|string $value): string => (string)$value;

        yield 'equal' => ['10', 10, $toStringExtractor, Order::Equal];
        yield 'greater' => ['10', 0.3, $toStringExtractor, Order::Greater];
        yield 'lesser' => [3, '10', $toStringExtractor, Order::Lesser];
    }

    public static function incompatibleValuesProvider(): iterable
    {
        yield 'floats' => [0.1, 0.2];
        yield 'integers' => [1, 2];
        yield 'a-not-compatible' => [1, 'foo'];
        yield 'b-not-compatible' => ['foo', 1];
    }

    #[Test]
    #[DataProvider("comparableValuesProvider")]
    public function itCanCompareValues(string|Stringable $a, string|Stringable $b, Order $expected): void
    {
        $comparator = Comparators::strings();

        $this->assertEquals($expected, $comparator->compare($a, $b));
    }

    #[Test]
    #[DataProvider("incompatibleValuesProvider")]
    public function itThrowsIncomparableExceptionForIncompatibleValues(mixed $a, mixed $b): void
    {
        $comparator = Comparators::strings();

        $this->expectException(IncomparableException::class);
        $comparator->compare($a, $b);
    }

    /**
     * @param pure-callable(mixed): string $extractor
     */
    #[Test]
    #[DataProvider("comparableValuesUsingExtractorProvider")]
    public function itWillCompareValuesFromExtractor(mixed $a, mixed $b, callable $extractor, Order $expected): void
    {
        $comparator = Comparators::strings($extractor);

        $this->assertEquals($expected, $comparator->compare($a, $b));
    }
}
