<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream\MixedStream;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class MapTest extends TestCase
{
    #[Test]
    public function itTransformsElementsViaMapper(): void
    {
        $stream = MixedStream::fromIterable(range(1, 5));

        self::assertEquals(
            ['1', '2', '3', '4', '5'],
            $stream->map(static fn(int $value): string => (string) $value)
        );
    }
}
