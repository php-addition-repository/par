<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit;

use Par\Core\Exception\NoSuchElementException;
use Par\Core\Optional;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class OptionalTest extends TestCase
{
    public static function allValuesProvider(): iterable
    {
        yield from self::nonNullableValuesProvider();
        yield [null];
    }

    public static function equalsProvider(): iterable
    {
        yield 'both-empty' => [Optional::empty(), Optional::empty(), true];
        yield 'same-value' => [Optional::fromAny('foo'), Optional::fromAny('foo'), true];
        yield 'different-value' => [Optional::fromAny('foo'), Optional::fromAny('bar'), false];
        yield 'value-vs-empty' => [Optional::fromAny('foo'), Optional::empty(), false];
        yield 'value-vs-other' => [Optional::fromAny('foo'), false, false];
        yield 'value-vs-internal-value' => [Optional::fromAny('foo'), 'foo', false];
    }

    public static function nonNullableValuesProvider(): iterable
    {
        yield ['foo'];
        yield [''];
        yield [true];
        yield [false];
        yield [0];
        yield [1];
        yield [new \stdClass()];
        yield [[]];
    }

    #[Test]
    public function filterReturnsEmptyOptionalWhenPredicateNotMatches(): void
    {
        $optional = Optional::fromAny('bar');

        $this->assertEquals(Optional::empty(), $optional->filter(static fn(string $value): bool => $value !== 'bar'));
    }

    #[Test]
    public function filterReturnsOptionalWhenPredicateMatches(): void
    {
        $optional = Optional::fromAny('foo');

        $this->assertEquals($optional, $optional->filter(static fn(string $value): bool => $value === 'foo'));
    }

    #[Test]
    public function ifPresentDoesNotExecuteActionIfEmpty(): void
    {
        $optional = Optional::empty();

        $invocations = [];
        $optional->ifPresent(
            static function (string $value) use (&$invocations): void {
                $invocations[] = $value;
            },
        );

        $this->assertEquals([], $invocations);
    }

    #[Test]
    public function ifPresentExecutesActionIfNotEmpty(): void
    {
        $optional = Optional::fromAny('foo');

        $invocations = [];
        $optional->ifPresent(
            static function (string $value) use (&$invocations): void {
                $invocations[] = $value;
            },
        );

        $this->assertEquals(['foo'], $invocations);
    }

    #[Test]
    public function ifPresentOrElseEmptyExecutesActionIfEmpty(): void
    {
        $optional = Optional::empty();

        $invocations = [];
        $optional->ifPresentOrElse(
            static function (string $value) use (&$invocations): void {
                $invocations[] = $value;
            },
            static function () use (&$invocations): void {
                $invocations[] = 'empty';
            },
        );

        $this->assertEquals(['empty'], $invocations);
    }

    #[Test]
    public function ifPresentOrElseExecutesActionIfNotEmpty(): void
    {
        $optional = Optional::fromAny('foo');

        $invocations = [];
        $optional->ifPresentOrElse(
            static function (string $value) use (&$invocations): void {
                $invocations[] = $value;
            },
            static function () use (&$invocations): void {
                $invocations[] = '<empty>';
            },
        );

        $this->assertEquals(['foo'], $invocations);
    }

    #[Test]
    #[DataProvider("equalsProvider")]
    public function itCanDetermineEquality(Optional $subject, mixed $other, bool $expected): void
    {
        $this->assertEquals($expected, $subject->equals($other));
    }

    #[Test]
    public function itHasNoValueWhenConstructedEmpty(): void
    {
        $optional = Optional::empty();

        $this->assertFalse($optional->isPresent());
        $this->assertTrue($optional->isEmpty());

        $this->expectException(NoSuchElementException::class);
        $optional->get();
    }

    #[Test]
    #[DataProvider("allValuesProvider")]
    public function itHasValueWhenConstructedFromAny(mixed $a): void
    {
        $optional = Optional::fromAny($a);

        $this->assertTrue($optional->isPresent());
        $this->assertFalse($optional->isEmpty());
        $this->assertEquals($a, $optional->get());
    }

    #[Test]
    #[DataProvider("nonNullableValuesProvider")]
    public function itHasValueWhenConstructedFromNullableWithNonNull(mixed $a): void
    {
        $optional = Optional::fromNullable($a);

        $this->assertTrue($optional->isPresent());
        $this->assertFalse($optional->isEmpty());
        $this->assertEquals($a, $optional->get());
    }

    #[Test]
    public function itIsEmptyWhenConstructedFromNullableWithNull(): void
    {
        $optional = Optional::fromNullable(null);

        $this->assertFalse($optional->isPresent());
        $this->assertTrue($optional->isEmpty());

        $this->expectException(NoSuchElementException::class);
        $optional->get();
    }

    #[Test]
    public function mapReturnsEmptyOptionalWhenEmpty(): void
    {
        $optional = Optional::empty();

        $this->assertEquals(Optional::empty(), $optional->map(static fn(string $value): string => $value . '-mapped'));
    }

    #[Test]
    public function mapReturnsOptionalWithResultFromMapperWhenNotEmpty(): void
    {
        $optional = Optional::fromAny('foo');

        $this->assertEquals(
            Optional::fromAny('foo-mapped'),
            $optional->map(static fn(string $value): string => $value . '-mapped')
        );
    }

    #[Test]
    public function orElseGetReturnsResponseFromSupplierWhenEmpty(): void
    {
        $optional = Optional::empty();

        $otherValue = 'foo';

        $this->assertEquals($otherValue, $optional->orElseGet(static fn(): string => $otherValue));
    }

    #[Test]
    public function orElseGetReturnsValueWhenNotEmpty(): void
    {
        $value = 'foo';
        $optional = Optional::fromAny($value);

        $otherValue = 'bar';

        $this->assertEquals($value, $optional->orElseGet(static fn(): string => $otherValue));
    }

    #[Test]
    public function orElseReturnsOtherValueWhenEmpty(): void
    {
        $optional = Optional::empty();

        $otherValue = 'bar';

        $this->assertEquals($otherValue, $optional->orElse($otherValue));
    }

    #[Test]
    public function orElseReturnsValueWhenNotEmpty(): void
    {
        $value = 'foo';
        $optional = Optional::fromAny($value);

        $otherValue = 'bar';

        $this->assertEquals($value, $optional->orElse($otherValue));
    }
}
