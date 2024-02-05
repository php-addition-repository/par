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
                $this->assertEquals($value, $matcher->numberOfInvocations());
            });

        $this->assertNotSame($stream, $peekedStream = $stream->peek($invoker));

        // Need to call a terminal operation on the peeked stream to trigger the peeking.
        $peekedStream->noneMatch(static fn() => false);
    }
}
