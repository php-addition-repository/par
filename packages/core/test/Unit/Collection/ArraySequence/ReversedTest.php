<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArraySequence;

use Par\Core\Collection\ArraySequence;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ReversedTest extends TestCase
{
    #[Test]
    public function itReturnsWithElementsReversed(): void
    {
        $range = range('a', 'e');
        $sequence = ArraySequence::fromIterable($range);
        $reversedSequence = $sequence->reversed();

        self::assertEquals(ArraySequence::fromIterable(array_reverse($range)), $reversedSequence);
        self::assertNotSame($sequence, $reversedSequence);
        self::assertNotEquals($sequence, $reversedSequence);
    }
}
