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
final class ContainsTest extends TestCase
{
    public static function provideForContains(): iterable
    {
        yield 'empty' => [
            ArraySequence::empty(),
            'foo',
            false,
        ];

        $objectSequence = ArraySequence::fromIterable(EquableScalarObject::fromIntRange(1, 5));

        yield 'contained' => [
            $objectSequence,
            EquableScalarObject::fromInt(3),
            true,
        ];
        yield 'not-contained' => [
            $objectSequence,
            EquableScalarObject::fromInt(7),
            false,
        ];
    }

    #[Test]
    #[DataProvider('provideForContains')]
    public function itCanDetermineIfItContainsElement(
        ArraySequence $sequence,
        mixed $element,
        bool $expectedResult
    ): void {
        self::assertSame($expectedResult, $sequence->contains($element));
    }
}
