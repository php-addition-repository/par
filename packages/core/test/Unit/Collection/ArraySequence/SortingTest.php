<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArraySequence;

use Par\Core\Collection\ArraySequence;
use Par\Core\Collection\MutableSequence;
use Par\Core\Collection\SequencedCollection;
use Par\Core\Comparison\Comparator;
use Par\Core\Comparison\Comparators;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class SortingTest extends TestCase
{
    public static function provideForSorted(): iterable
    {
        yield 'default' => [
            ArraySequence::fromIterable(['bar', 'foo', 'baz']),
            ['bar', 'baz', 'foo'],
            null,
        ];

        yield 'comparator' => [
            ArraySequence::fromIterable(['bar', 'foo', 'baz']),
            ['bar', 'baz', 'foo'],
            Comparators::strings(),
        ];

        yield 'callable' => [
            ArraySequence::fromIterable(['bar', 'foo', 'baz']),
            ['bar', 'baz', 'foo'],
            static fn(string $a, string $b): int => $a <=> $b,
        ];
    }

    #[Test]
    #[DataProvider('provideForSorted')]
    public function itWillReturnNewSortedSequence(
        SequencedCollection $sequence,
        iterable $sortedElements,
        callable|Comparator|null $comparator
    ): void {
        $sortedSequence = $sequence->sorted($comparator);
        self::assertEquals($sortedElements, $sortedSequence);
        self::assertNotSame($sequence, $sortedSequence);
    }

    #[Test]
    #[DataProvider('provideForSorted')]
    public function itWillSortTheSequence(
        MutableSequence $sequence,
        iterable $sortedElements,
        callable|Comparator|null $comparator
    ): void {
        $sortedSequence = $sequence->sort($comparator);
        self::assertEquals($sortedElements, $sortedSequence);
        self::assertSame($sequence, $sortedSequence);
    }
}
