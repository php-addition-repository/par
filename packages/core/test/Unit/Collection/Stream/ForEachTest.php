<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Stream;

use Par\Core\Collection\Stream;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class ForEachTest extends TestCase
{
    #[Test]
    public function itExecutesActionForEachElement(): void
    {
        $stream = Stream::fromIterable(range(1, 5));

        $counter = 0;
        $stream->forEach(static function () use (&$counter): void {
            $counter += 1;
        });

        $this->assertSame(5, $counter);
    }
}
