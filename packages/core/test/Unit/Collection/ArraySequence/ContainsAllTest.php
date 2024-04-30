<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArraySequence;

use Par\Core\Collection\ArraySequence;
use Par\CoreTest\Fixtures\EquableScalarObject;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ContainsAllTest extends TestCase
{
    public static function provideForContainsAll(): iterable
    {
        $sequence = ArraySequence::fromIterable(EquableScalarObject::fromIntRange(1, 5));

        yield 'all' => [
            $sequence,
            EquableScalarObject::fromIntRange(1, 5),
            true,
        ];

        yield 'subset' => [
            $sequence,
            EquableScalarObject::fromIntRange(2, 4),
            true,
        ];

        yield 'some' => [
            $sequence,
            [
                EquableScalarObject::fromInt(1),
                EquableScalarObject::fromString('a'),
                EquableScalarObject::fromInt(2),
                EquableScalarObject::fromInt(3),
            ],
            false,
        ];

        yield 'other' => [
            $sequence,
            [
                EquableScalarObject::fromString('a'),
                EquableScalarObject::fromString('b'),
            ],
            false,
        ];

        yield 'none' => [
            $sequence,
            [],
            true,
        ];

        yield 'empty:any' => [
            ArraySequence::empty(),
            [EquableScalarObject::fromInt(1)],
            false,
        ];
    }

    #[Test]
    #[DataProvider('provideForContainsAll')]
    public function itCanDetermineThatItContainsAllElements(
        ArraySequence $sequence,
        iterable $containsElements,
        bool $expectedResult
    ): void {
        self::assertSame($expectedResult, $sequence->containsAll($containsElements));
    }
}
