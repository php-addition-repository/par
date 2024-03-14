<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream\MixedStream;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class MapToFloatTest extends TestCase
{
    #[Test]
    public function itCastsElementsToIntWithoutMapper(): void
    {
        $stream = MixedStream::fromIterable(range(1, 5));

        self::assertEquals(range(1.0, 5.0), $stream->mapToFloat());
    }

    #[Test]
    public function itTransformsElementsViaMapper(): void
    {
        $stream = MixedStream::fromIterable(range('a', 'e'));

        self::assertEquals(
            [0.1, 0.1, 0.1, 1.1, 1.1],
            $stream->mapToFloat(static fn(string $value): float => $value > 'c' ? 1.1 : 0.1)
        );
    }
}
