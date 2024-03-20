<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArrayMap;

use Par\Core\Collection\ArrayMap;
use Par\Core\Collection\Stream\MixedStream;
use Par\Core\Collection\Stream\Stream;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class StreamTest extends TestCase
{
    public static function provideForStreaming(): iterable
    {
        yield 'empty' => [
            ArrayMap::empty(),
            MixedStream::empty(),
        ];

        yield 'string[]' => [
            ArrayMap::fromIterable(range('a', 'e')),
            MixedStream::fromIterable(range('a', 'e')),
        ];

        yield 'array<string,int>' => [
            ArrayMap::fromArray(['foo' => 3, 'bar' => 4, 'baz' => 5]),
            MixedStream::fromIterable([3, 4, 5]),
        ];
    }

    #[Test]
    #[DataProvider('provideForStreaming')]
    public function itCanBeStreamed(ArrayMap $sequence, Stream $expectedStream): void
    {
        self::assertEquals($expectedStream, $sequence->stream());
    }

    #[Test]
    public function streamIsNotAffectedBySubsequentChangeToSource(): void
    {
        $map = ArrayMap::fromArray(['foo' => 3, 'bar' => 4, 'baz' => 5]);

        $stream = $map->stream();

        $map->put('foobar', 6);

        self::assertEquals(MixedStream::fromIterable([3, 4, 5]), $stream);
        self::assertCount(3, $stream);
    }
}
