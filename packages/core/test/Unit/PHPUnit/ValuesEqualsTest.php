<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\PHPUnit;

use Par\Core\Values;
use Par\CoreTest\Fixtures\ScalarValueObject;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ValuesEqualsTest extends TestCase
{
    public static function provideForEquality(): iterable
    {
        yield 'same-scalar' => ['foo', 'foo', true];
        yield 'different-scalar' => ['foo', 1, false];

        $dateTime = new \DateTime('2023-11-28 16:16:23');
        yield 'same-datetime-instances' => [$dateTime, $dateTime, true];

        $sameDateTime = new \DateTimeImmutable('2023-11-28 16:16:23');
        yield 'same-datetime-values' => [$dateTime, $sameDateTime, true];

        $otherDateTime = new \DateTimeImmutable('2023-11-28 16:16:24');
        yield 'different-datetime-values' => [$dateTime, $otherDateTime, false];

        $valueObject = new ScalarValueObject('foo');
        yield 'same-object-equality-instances' => [$valueObject, $valueObject, true];

        $sameValueObject = new ScalarValueObject('foo');
        yield 'same-object-equality-values' => [$valueObject, $sameValueObject, true];

        $otherValueObject = new ScalarValueObject(1);
        yield 'different-object-equality-values' => [$valueObject, $otherValueObject, false];
    }

    #[Test]
    #[DataProvider("provideForEquality")]
    public function itCanDetermineIfTwoValuesShouldBeConsideredEqual(
        mixed $value,
        mixed $otherValue,
        bool $expectedEqual
    ): void {
        $this->assertEquals($expectedEqual, Values::equals($value, $otherValue));
    }
}
