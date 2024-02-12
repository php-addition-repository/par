<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Sequence;

use Par\Core\Collection\Sequence;
use Par\Core\Collection\Vector;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class LastIndexOfTest extends TestCase
{
    public static function provideForVector(): iterable
    {
        yield 'Vector:existing-element' => [Vector::fromIterable([...range('a', 'e'), ...range('a', 'e')]), 'd', 8];
        yield 'Vector:unknown-element' => [Vector::fromIterable(range('a', 'e')), 'z', -1];
        yield 'Vector:empty' => [Vector::empty(), 'd', -1];
    }

    #[Test]
    #[DataProvider('provideForVector')]
    public function itCanDetermineIndexOfElement(
        Sequence $sequencedCollection,
        mixed $element,
        int $expectedIndex
    ): void {
        self::assertEquals($expectedIndex, $sequencedCollection->lastIndexOf($element));
    }
}
