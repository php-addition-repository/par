<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArraySequence;

use Par\Core\Collection\ArraySequence;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class IsEmptyTest extends TestCase
{
    #[Test]
    public function itReturnsTrueForEmptyStream(): void
    {
        $stream = ArraySequence::empty();

        self::assertTrue($stream->isEmpty());
    }

    #[Test]
    public function itReturnsFalseForStreamWithElements(): void
    {
        $stream = ArraySequence::fromIterable(range(1, 5));

        self::assertFalse($stream->isEmpty());
    }
}
