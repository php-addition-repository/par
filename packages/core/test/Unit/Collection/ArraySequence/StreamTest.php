<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArraySequence;

use Par\Core\Collection\ArraySequence;
use Par\Core\Collection\Stream;
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
            ArraySequence::empty(),
            Stream::empty(),
        ];

        yield 'string[]' => [
            ArraySequence::fromIterable(range('a', 'e')),
            Stream::fromIterable(range('a', 'e')),
        ];
    }

    #[Test]
    #[DataProvider('provideForStreaming')]
    public function itCanBeStreamed(ArraySequence $sequence, Stream $expectedStream): void
    {
        self::assertEquals($expectedStream, $sequence->stream());
    }

    #[Test]
    public function streamIsNotAffectedBySubsequentChangeToSource(): void
    {
        $sequence = ArraySequence::fromIterable(range(1, 5));

        $stream = $sequence->stream();

        $sequence->add(6);

        self::assertEquals(Stream::fromIterable(range(1, 5)), $stream);
        self::assertCount(5, $stream);
    }
}
