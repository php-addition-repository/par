<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit;

use Par\Core\Values;
use Par\CoreTest\Fixtures\ScalarValueObject;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ValuesEqualsTest extends TestCase
{
    public static function provideForEquals(): iterable
    {
        $valueTypes = [
            'string' => ['foo', 'foo'],
            'int' => [1, 2],
            'float' => [0.1, 0.2],
            'bool' => [true, false],
            'array' => [['foo'], ['bar']],
            'object' => [new \stdClass(), new \stdClass()],
            'object-equality' => [new ScalarValueObject('foo'), new ScalarValueObject('bar')],
        ];

        foreach ($valueTypes as $type => $values) {
            foreach ($values as $key => $value) {
                if ($key === 0) {
                    yield 'same-' . $type . '-value' => [$value, $value, true];
                } else {
                    yield 'different-' . $type . '-value' => [$values[0], $value, false];
                }
            }

            yield $type . '-vs-null' => [$values[0], null, false];
        }

        $dateTime = new \DateTime('2023-11-28 16:16:23');
        yield 'same-datetime-instances' => [$dateTime, $dateTime, true];

        $sameDateTime = new \DateTimeImmutable('2023-11-28 16:16:23');
        yield 'same-datetime-values' => [$dateTime, $sameDateTime, true];

        $otherDateTime = new \DateTimeImmutable('2023-11-28 16:16:24');
        yield 'different-datetime-values' => [$dateTime, $otherDateTime, false];
    }

    public static function provideForEqualsOneOf(): iterable
    {
        $list = [1, 2, 'bar', 3, 4, 'baz', null, new \stdClass()];

        yield 'in-mixed-list' => [3, $list, true];
        yield 'not-in-mixed-list' => ['foo', $list, false];

        yield 'in-object-equality-list' => [
            new ScalarValueObject('foo'),
            [
                new ScalarValueObject('bar'),
                new ScalarValueObject('foo'),
                new ScalarValueObject('baz')
            ],
            true
        ];

        yield 'not-in-object-equality-list' => [
            new ScalarValueObject('foobar'),
            [
                new ScalarValueObject('bar'),
                new ScalarValueObject('foo'),
                new ScalarValueObject('baz')
            ],
            false
        ];

        yield 'no-other-values' => [1, [], false];
    }

    #[Test]
    #[DataProvider("provideForEquals")]
    public function itCanDetermineIfTwoValuesShouldBeConsideredEqual(
        mixed $value,
        mixed $otherValue,
        bool $expectedEqual
    ): void {
        $this->assertEquals($expectedEqual, Values::equals($value, $otherValue));
    }

    #[Test]
    #[DataProvider("provideForEqualsOneOf")]
    public function itCanDetermineIfValueShouldBeConsideredEqualToOneOfOtherValues(
        mixed $value,
        array $otherValues,
        bool $expectedEqual
    ): void {
        $this->assertEquals($expectedEqual, Values::equalsOneOf($value, ...$otherValues));
    }

    #[Test]
    #[DataProvider("provideForEqualsOneOf")]
    public function itCanDetermineIfValueShouldBeConsideredEqualToNoneOfOtherValues(
        mixed $value,
        array $otherValues,
        bool $expectedEqual
    ): void {
        $this->assertNotEquals($expectedEqual, Values::equalsNoneOf($value, ...$otherValues));
    }
}
