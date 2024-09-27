<?php

declare(strict_types=1);

namespace ParTest\Core\Unit;

use ArrayIterator;
use DateTime;
use DateTimeImmutable;
use Par\Core\Values;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @internal
 */
final class ValuesTest extends TestCase
{
    public static function equalsOneOfProvider(): iterable
    {
        $list = [1, 2, 'bar', 3, 4, 'baz', null, new stdClass()];

        yield 'in-mixed-list' => [3, $list, true];
        yield 'not-in-mixed-list' => ['foo', $list, false];

        //        yield 'in-object-equality-list' => [
        //            EquableScalarObject::fromString('foo'),
        //            [
        //                EquableScalarObject::fromString('bar'),
        //                EquableScalarObject::fromString('foo'),
        //                EquableScalarObject::fromString('baz'),
        //            ],
        //            true,
        //        ];
        //
        //        yield 'not-in-object-equality-list' => [
        //            EquableScalarObject::fromString('foobar'),
        //            [
        //                EquableScalarObject::fromString('bar'),
        //                EquableScalarObject::fromString('foo'),
        //                EquableScalarObject::fromString('baz'),
        //            ],
        //            false,
        //        ];

        yield 'no-other-values' => [1, [], false];
    }

    public static function equalsProvider(): iterable
    {
        $valueTypes = [
            'string' => ['foo', 'bar'],
            'int' => [1, 2],
            'float' => [0.1, 0.2],
            'bool' => [true, false],
            'array' => [['foo'], ['bar']],
            'object' => [new stdClass(), new stdClass()],
            //            'object-equality' => [EquableScalarObject::fromString('foo'), EquableScalarObject::fromString('bar')],
        ];

        foreach ($valueTypes as $type => $values) {
            foreach ($values as $key => $value) {
                if (0 === $key) {
                    yield 'same-' . $type . '-value' => [$value, $value, true];
                } else {
                    yield 'different-' . $type . '-value' => [$values[0], $value, false];
                }
            }

            yield $type . '-vs-null' => [$values[0], null, false];
        }

        //        yield 'null-vs-object-equality' => [null, EquableScalarObject::fromString('foo'), false];

        $dateTime = new DateTime('2023-11-28 16:16:23');
        yield 'same-datetime-instances' => [$dateTime, $dateTime, true];

        $sameDateTime = new DateTime('2023-11-28 16:16:23');
        yield 'same-datetime-values' => [$dateTime, $sameDateTime, true];

        $otherDateTime = new DateTime('2023-11-28 16:16:24');
        yield 'different-datetime-values' => [$dateTime, $otherDateTime, false];

        $dateTime = new DateTimeImmutable('2023-11-28 16:16:23');
        yield 'same-datetime-immutable-instances' => [$dateTime, $dateTime, true];

        $sameDateTime = new DateTimeImmutable('2023-11-28 16:16:23');
        yield 'same-datetime-immutable-values' => [$dateTime, $sameDateTime, true];

        $otherDateTime = new DateTimeImmutable('2023-11-28 16:16:24');
        yield 'different-datetime-immutable-values' => [$dateTime, $otherDateTime, false];
    }

    #[DataProvider('equalsProvider')]
    public function testItCanDetermineEqualityBetweenTwoValues(
        mixed $value,
        mixed $otherValue,
        bool $expectedEqual,
    ): void {
        self::assertSame($expectedEqual, Values::equals($value, $otherValue));
    }

    #[DataProvider('equalsOneOfProvider')]
    public function testItCanDetermineIfValueShouldBeConsideredEqualToNoneOfOtherValues(
        mixed $value,
        array $otherValues,
        bool $expectedEqual,
    ): void {
        self::assertNotEquals($expectedEqual, Values::equalsNoneOf($value, ...$otherValues));
        self::assertNotEquals($expectedEqual, Values::equalsNoneIn($value, $otherValues));
    }

    #[DataProvider('equalsOneOfProvider')]
    public function testItCanDetermineIfValueShouldBeConsideredEqualToOneOfOtherValues(
        mixed $value,
        array $otherValues,
        bool $expectedEqual,
    ): void {
        self::assertEquals($expectedEqual, Values::equalsAnyOf($value, ...$otherValues));
        self::assertEquals($expectedEqual, Values::equalsAnyIn($value, $otherValues));
    }
}
