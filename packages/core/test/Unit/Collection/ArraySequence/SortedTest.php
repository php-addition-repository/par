<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArraySequence;

use Par\Core\Collection\ArraySequence;
use Par\Core\Comparison\Comparator;
use Par\Core\Comparison\Comparators;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class SortedTest extends TestCase
{
    public static function provideForSorting(): iterable
    {
        $range = range(1, 5);
        $reversedRange = array_reverse($range);

        yield 'callable' => [
            ArraySequence::fromIterable($reversedRange),
            static fn(int $a, int $b): int => $a <=> $b,
            ArraySequence::fromIterable($range),
        ];
        yield 'Comparator' => [
            ArraySequence::fromIterable($range),
            Comparators::values()->reversed(),
            ArraySequence::fromIterable($reversedRange),
        ];
        yield 'default' => [
            ArraySequence::fromIterable($reversedRange),
            null,
            ArraySequence::fromIterable($range),
        ];
    }

    /**
     * @param callable|Comparator<mixed>|null $comparator
     */
    #[Test]
    #[DataProvider('provideForSorting')]
    public function itReturnsSorted(
        ArraySequence $sequence,
        callable|Comparator|null $comparator,
        ArraySequence $expectedSequence
    ): void {
        $sorted = $sequence->sorted($comparator);

        self::assertEquals($expectedSequence, $sorted);
        self::assertNotSame($sequence, $sorted);
        self::assertNotEquals($sequence, $sorted);
    }
}
