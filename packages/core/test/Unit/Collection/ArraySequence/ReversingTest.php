<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArraySequence;

use Par\Core\Collection\ArraySequence;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ReversingTest extends TestCase
{
    #[Test]
    public function itWillReturnNewReversedSequence(): void
    {
        $sequence = ArraySequence::fromIterable(['bar', 'foo', 'baz']);

        self::assertEquals(['baz', 'foo', 'bar'], $sequence->reversed());
        self::assertEquals(['bar', 'foo', 'baz'], $sequence);
    }

    #[Test]
    public function itWillReverseTheSequence(): void
    {
        $sequence = ArraySequence::fromIterable(['bar', 'foo', 'baz']);

        self::assertEquals(['baz', 'foo', 'bar'], $sequence->reverse());
        self::assertEquals(['baz', 'foo', 'bar'], $sequence);
    }
}
