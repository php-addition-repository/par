<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\SequencedCollection;

use Par\Core\Collection\ArraySequence;
use Par\Core\Collection\SequencedCollection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ReversedTest extends TestCase
{
    public static function provideForVector(): iterable
    {
        $range = range('a', 'e');

        yield 'Vector:string[]' => [ArraySequence::fromIterable($range), ArraySequence::fromIterable(array_reverse($range))];
    }

    #[Test]
    #[DataProvider('provideForVector')]
    public function itReturnsWithElementsReversed(
        SequencedCollection $sequencedCollection,
        SequencedCollection $expectedSequencedCollection
    ): void {
        $reversed = $sequencedCollection->reversed();

        self::assertEquals($expectedSequencedCollection, $reversed);
        self::assertNotSame($sequencedCollection, $reversed);
        self::assertNotEquals($sequencedCollection, $reversed);
    }
}
