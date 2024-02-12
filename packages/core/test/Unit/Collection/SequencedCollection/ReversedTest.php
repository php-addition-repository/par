<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\SequencedCollection;

use Par\Core\Collection\SequencedCollection;
use Par\Core\Collection\Vector;
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

        yield 'Vector:string[]' => [Vector::fromIterable($range), Vector::fromIterable(array_reverse($range))];
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
