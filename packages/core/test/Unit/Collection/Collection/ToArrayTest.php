<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Collection;

use Par\Core\Collection\ArraySequence;
use Par\Core\Collection\Collection;
use Par\CoreTest\Fixtures\EquableScalarObject;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ToArrayTest extends TestCase
{
    public static function provideForVector(): iterable
    {
        yield 'Vector:[]]' => [ArraySequence::empty(), []];
        yield 'Vector:string[]' => [ArraySequence::fromIterable(range('a', 'e')), range('a', 'e')];
        yield 'Vector:object[]' => [
            ArraySequence::fromIterable(EquableScalarObject::generateList(range('a', 'e'))),
            iterator_to_array(EquableScalarObject::generateList(range('a', 'e'))),
        ];
    }

    /**
     * @param Collection<array-key, mixed> $collection
     */
    #[Test]
    #[DataProvider('provideForVector')]
    public function itCanBeTransformedToArray(Collection $collection, array $expectedArray): void
    {
        self::assertEquals($expectedArray, $collection->toArray());
    }
}
