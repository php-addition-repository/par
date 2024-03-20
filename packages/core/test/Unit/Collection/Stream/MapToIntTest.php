<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream\MixedStream;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class MapToIntTest extends TestCase
{
    #[Test]
    public function itCastsElementsToIntWithoutMapper(): void
    {
        $stream = MixedStream::fromIterable(range(0.1, 5.1));

        self::assertEquals(range(0, 5), $stream->mapToInt());
    }

    #[Test]
    public function itTransformsElementsViaMapper(): void
    {
        $stream = MixedStream::fromIterable(range('a', 'e'));

        self::assertEquals([0, 0, 0, 1, 1], $stream->mapToInt(static fn(string $value): int => $value > 'c' ? 1 : 0));
    }
}
