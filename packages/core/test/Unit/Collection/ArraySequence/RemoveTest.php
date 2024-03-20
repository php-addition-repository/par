<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArraySequence;

use Par\Core\Collection\ArraySequence;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class RemoveTest extends TestCase
{
    #[Test]
    public function itWillRemoveElementIfPresentAndReturnTrue(): void
    {
        $sequence = ArraySequence::fromIterable(range('a', 'e'));

        self::assertTrue($sequence->remove('c'));
        self::assertEquals(['a', 'b', 'd', 'e'], $sequence);
    }

    #[Test]
    public function itWillNotRemoveElementIfNotPresentAndReturnFalse(): void
    {
        $sequence = ArraySequence::fromIterable(range('a', 'e'));

        self::assertFalse($sequence->remove('z'));
        self::assertEquals(range('a', 'e'), $sequence);
    }

    #[Test]
    public function itWillRemoveFirstElement(): void
    {
        $sequence = ArraySequence::fromIterable(range(1, 5));

        $sequence->removeFirst();

        self::assertEquals(range(2, 5), $sequence);
    }

    #[Test]
    public function itWillRemoveLastElement(): void
    {
        $sequence = ArraySequence::fromIterable(range(1, 5));

        $sequence->removeLast();

        self::assertEquals(range(1, 4), $sequence);
    }

    #[Test]
    public function itWillRemoveAllElementMatchingPredicate(): void
    {
        $sequence = ArraySequence::fromIterable(range(1, 5));

        $sequence->removeIf(static fn(int $element): bool => (bool) ($element % 2));

        self::assertEquals([1, 3, 5], $sequence);
    }
}
