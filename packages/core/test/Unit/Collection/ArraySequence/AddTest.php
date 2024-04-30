<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArraySequence;

use Par\Core\Collection\ArraySequence;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AddTest extends TestCase
{
    #[Test]
    public function itWillAddElement(): void
    {
        $sequence = ArraySequence::fromIterable(['bar', 'foo']);

        $sequence->add('baz');
        $sequence->addLast('a');

        self::assertEquals(['bar', 'foo', 'baz', 'a'], $sequence);
    }

    #[Test]
    public function itWillAddAllElementsInOrder(): void
    {
        $sequence = ArraySequence::fromIterable([5, 6, 7]);

        $sequence->addAll(range(1, 4));

        self::assertEquals([5, 6, 7, 1, 2, 3, 4], $sequence);
    }

    #[Test]
    public function itWillAddElementInFirstPosition(): void
    {
        $sequence = ArraySequence::fromIterable(range(4, 6));

        $sequence->addFirst(1);

        self::assertEquals([1, 4, 5, 6], $sequence);
    }
}
