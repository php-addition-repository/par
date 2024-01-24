<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream;
use Par\CoreTest\Fixtures\Invokable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PeekTest extends TestCase
{
    #[Test]
    public function itPeeksEachElementOnceIterated(): void
    {
        $stream = Stream::fromIterable(range(1, 5));

        $invoker = $this->createMock(Invokable::class);
        $matcher = $this->exactly(5);
        $invoker->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(function (int $value) use ($matcher) {
                /** @psalm-suppress InternalMethod */
                match ($matcher->numberOfInvocations()) {
                    1 => $this->assertEquals(1, $value),
                    2 => $this->assertEquals(2, $value),
                    3 => $this->assertEquals(3, $value),
                    4 => $this->assertEquals(4, $value),
                    5 => $this->assertEquals(5, $value),
                };
        });
        /** @psalm-var pure-callable $invoker */

        $this->assertNotSame($stream, $peekedStream = $stream->peek($invoker));

        // Need to call a terminal operation on the peeked stream to trigger the peeking.
        count($peekedStream);
    }
}
