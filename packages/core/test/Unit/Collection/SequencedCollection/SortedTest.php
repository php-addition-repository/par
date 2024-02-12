<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\SequencedCollection;

use Par\Core\Collection\ArraySequence;
use Par\Core\Collection\SequencedCollection;
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
    public static function provideForVector(): iterable
    {
        $range = range(1, 5);
        $reversedRange = array_reverse($range);

        yield 'Vector:callable' => [
            ArraySequence::fromIterable($reversedRange),
            static fn(int $a, int $b): int => $a <=> $b,
            ArraySequence::fromIterable($range),
        ];
        yield 'Vector:Comparator' => [
            ArraySequence::fromIterable($range),
            Comparators::values()->reversed(),
            ArraySequence::fromIterable($reversedRange),
        ];
        yield 'Vector:default' => [
            ArraySequence::fromIterable($reversedRange),
            null,
            ArraySequence::fromIterable($range),
        ];
    }

    /**
     * @param callable|Comparator<mixed>|null $comparator
     */
    #[Test]
    #[DataProvider('provideForVector')]
    public function itReturnsSorted(
        SequencedCollection $sequencedCollection,
        callable|Comparator|null $comparator,
        SequencedCollection $expectedSequencedCollection
    ): void {
        $sorted = $sequencedCollection->sorted($comparator);

        self::assertEquals($expectedSequencedCollection, $sorted);
        self::assertNotSame($sequencedCollection, $sorted);
        self::assertNotEquals($sequencedCollection, $sorted);
    }
}
