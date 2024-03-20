<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Generator;
use Par\Core\Collection\Stream\FloatStream;
use Par\Core\Collection\Stream\IntStream;
use Par\Core\Collection\Stream\MixedStream;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ConstructorsTest extends TestCase
{
    public static function floatRangeProvider(): iterable
    {
        yield 'default step' => [[0.1, 5.1], [0.1, 1.1, 2.1, 3.1, 4.1, 5.1]];
        yield 'skip step' => [[1.0, 10.0, 2.0], [1.0, 3.0, 5.0, 7.0, 9.0]];
        yield 'negative start' => [[-5.0, 5.0, 2.0], [-5.0, -3.0, -1.0, 1.0, 3.0, 5.0]];
        yield 'default args' => [[], range(0.0, 9.0), 10];
        yield 'zero step' => [[0.0, PHP_INT_MAX, 0.0], array_pad([], 10, 0.0), 10];
    }

    public static function fromCallableProvider(): iterable
    {
        yield 'returning generator' => [
            static fn(int $a, int $b): Generator => yield from range($a, $b),
            [1, 5],
            range(1, 5),
        ];

        yield 'returning array' => [
            static fn(int $a, int $b): array => range($a, $b),
            [1, 5],
            range(1, 5),
        ];

        $classWithMethod = new class() {
            public function getValues(): Generator
            {
                yield from range(1, 5);
            }
        };
        yield 'class with method' => [[$classWithMethod, 'getValues'], [], range(1, 5)];

        $classWithStaticMethod = new class() {
            public static function getValues(): Generator
            {
                yield from range(1, 5);
            }
        };
        yield 'class with static method' => [[$classWithStaticMethod, 'getValues'], [], range(1, 5)];

        $invokableClass = new class() {
            public function __invoke(): Generator
            {
                yield from range(1, 5);
            }
        };
        yield 'invokable class' => [$invokableClass, [], range(1, 5)];
    }

    public static function fromGeneratorProvider(): iterable
    {
        $generator = (static function(): iterable {
            yield from range('a', 'e');
        })();

        yield 'generator' => [$generator, range('a', 'e')];

        $generator = (static function(): Generator {
            yield from range('a', 'e');
        })();
        $generator->next();
        $generator->next();

        yield 'forwarded generator' => [$generator, ['c', 'd', 'e']];
    }

    public static function fromIterableProvider(): iterable
    {
        yield 'array' => [range('A', 'F'), range('A', 'F')];

        $generator = (static function(): Generator {
            yield from range('a', 'e');
        })();
        $generator->next();
        $generator->next();
        yield 'forwarded generator' => [$generator, ['c', 'd', 'e']];

        $stream = IntStream::range(1, 5);
        yield 'stream' => [$stream, $stream];
    }

    public static function intRangeProvider(): iterable
    {
        yield 'default step' => [[0, 5], [0, 1, 2, 3, 4, 5]];
        yield 'skip step' => [[1, 10, 2], [1, 3, 5, 7, 9]];
        yield 'negative start' => [[-5, 5, 2], [-5, -3, -1, 1, 3, 5]];
        yield 'default args' => [[], range(0, 9), 10];
        yield 'zero step' => [[0, PHP_INT_MAX, 0], array_pad([], 10, 0), 10];
    }

    public static function timesProvider(): iterable
    {
        yield 'execute callable' => [
            [
                3,
                static fn(): array => range(1, 5),
            ],
            [
                range(1, 5),
                range(1, 5),
                range(1, 5),
            ],
        ];

        $callable = static fn(int $time): int => $time;

        yield 'negative times' => [
            [-5, $callable],
            [],
        ];
        yield 'zero times' => [
            [0, $callable],
            [],
        ];

        yield 'one time' => [
            [1, $callable],
            [1],
        ];
    }

    #[Test]
    public function empty(): void
    {
        self::assertEquals(
            [],
            MixedStream::empty()
        );
    }

    #[Test]
    #[DataProvider('floatRangeProvider')]
    public function floatRange(array $parameters, iterable $expectedIterable, int $limit = 0): void
    {
        $stream = FloatStream::range(...$parameters);
        if ($limit > 0) {
            $stream = $stream->limit($limit);
        }
        self::assertEquals(
            $expectedIterable,
            $stream
        );
    }

    /**
     * @param callable(mixed...): iterable $callable
     */
    #[Test]
    #[DataProvider('fromCallableProvider')]
    public function fromCallable(
        callable $callable,
        iterable $parameters,
        iterable $expectedIterable
    ): void {
        self::assertEquals(
            $expectedIterable,
            MixedStream::fromCallable($callable, $parameters)
        );
    }

    #[Test]
    #[DataProvider('fromGeneratorProvider')]
    public function fromGenerator(Generator $generator, iterable $expectedIterable): void
    {
        self::assertEquals(
            $expectedIterable,
            MixedStream::fromGenerator($generator)
        );
    }

    #[Test]
    #[DataProvider('fromIterableProvider')]
    public function fromIterable(iterable $iterable, iterable $expectedIterable): void
    {
        self::assertEquals(
            $expectedIterable,
            MixedStream::fromIterable($iterable)
        );
    }

    #[Test]
    #[DataProvider('intRangeProvider')]
    public function intRange(array $parameters, iterable $expectedIterable, int $limit = 0): void
    {
        $stream = IntStream::range(...$parameters);
        if ($limit > 0) {
            $stream = $stream->limit($limit);
        }
        self::assertEquals(
            $expectedIterable,
            $stream
        );
    }

    #[Test]
    #[DataProvider('timesProvider')]
    public function times(array $parameters, iterable $expectedIterable): void
    {
        self::assertEquals(
            $expectedIterable,
            MixedStream::times(...$parameters)
        );
    }
}
