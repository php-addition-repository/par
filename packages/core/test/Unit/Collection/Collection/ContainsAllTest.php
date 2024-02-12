<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Collection;

use Par\Core\Collection\Collection;
use Par\Core\Collection\Vector;
use Par\CoreTest\Fixtures\EquableScalarObject;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ContainsAllTest extends TestCase
{
    public static function provideForVector(): iterable
    {
        yield 'Vector:scalars-true' => [Vector::fromIterable(range(1, 5)), [3, 4], true];
        yield 'Vector:scalars-false' => [Vector::fromIterable(range(1, 5)), [5, 7], false];

        yield 'Vector:empty' => [Vector::empty(), ['foo'], false];

        yield 'Vector:equable-true' => [
            Vector::fromIterable(EquableScalarObject::generateList(range(1, 5))),
            EquableScalarObject::generateList(range(3, 4)),
            true,
        ];
        yield 'Vector:equable-false' => [
            Vector::fromIterable(EquableScalarObject::generateList(range(1, 5))),
            [new EquableScalarObject(5), new EquableScalarObject(7)],
            false,
        ];
    }

    /**
     * @param Collection<array-key, mixed> $collection
     */
    #[Test]
    #[DataProvider('provideForVector')]
    public function itCanDetermineIfAllElementsAreContained(
        Collection $collection,
        iterable $elements,
        bool $isContained
    ): void {
        self::assertSame($isContained, $collection->containsAll($elements));
    }
}
