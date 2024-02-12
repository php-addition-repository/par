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
final class ContainsTest extends TestCase
{
    public static function provideForVector(): iterable
    {
        yield 'Vector:scalar-true' => [Vector::fromIterable(range(1, 5)), 3, true];
        yield 'Vector:scalar-false' => [Vector::fromIterable(range(1, 5)), 7, false];

        yield 'Vector:empty' => [Vector::empty(), 'foo', false];

        yield 'Vector:equality-true' => [
            Vector::fromIterable(EquableScalarObject::generateList(range(1, 5))),
            new EquableScalarObject(3),
            true,
        ];
        yield 'Vector:equality-false' => [
            Vector::fromIterable(EquableScalarObject::generateList(range(1, 5))),
            new EquableScalarObject(7),
            false,
        ];
    }

    /**
     * @param Collection<array-key, mixed> $collection
     */
    #[Test]
    #[DataProvider('provideForVector')]
    public function itCanDetermineIfElementIsContained(Collection $collection, mixed $element, bool $isContained): void
    {
        self::assertSame($isContained, $collection->contains($element));
    }
}
