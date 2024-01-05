<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PeekTest extends TestCase
{
    #[Test]
    public function itPeeksEachElementOnceIterated(): void
    {
        $stream = Stream::fromIterable(range(1, 5));

        $counter = 0;
        $stream = $stream->peek(static function () use (&$counter): void {
            $counter += 1;
        });

        $this->assertSame(0, $counter);

        $expected = count($stream);
        $this->assertSame($expected, $counter);
    }
}
