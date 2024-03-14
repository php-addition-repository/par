<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream\MixedStream;
use Par\CoreTest\Fixtures\Invokable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ForEachTest extends TestCase
{
    #[Test]
    public function itExecutesActionForEachElement(): void
    {
        $stream = MixedStream::fromIterable(range(1, 5));

        $invoker = $this->createMock(Invokable::class);
        $matcher = self::exactly(5);
        $invoker->expects($matcher)
            ->method('__invoke')
            ->willReturnCallback(function(int $value) use ($matcher) {
                $this->assertEquals($value, $matcher->numberOfInvocations());
            });

        $stream->forEach($invoker);
    }
}
