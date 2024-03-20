<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArraySequence;

use Par\Core\Collection\ArraySequence;
use Par\Core\Exception\IndexOutOfBoundsException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class SetTest extends TestCase
{
    #[Test]
    public function itWillSetElementAtIndexAndReturnOriginalValue(): void
    {
        /** @var ArraySequence<string|int> $sequence */
        $sequence = ArraySequence::fromIterable(range(1, 5));

        self::assertEquals(3, $sequence->set(2, 'baz'));

        self::assertEquals([1, 2, 'baz', 4, 5], $sequence);
    }

    #[Test]
    public function itWillThrowOutOfBoundsExceptionWhenTryingToSetElementAtNegativeIndex(): void
    {
        $sequence = ArraySequence::fromIterable(range(1, 5));

        $this->expectException(IndexOutOfBoundsException::class);
        /* @phpstan-ignore-next-line */
        $sequence->set(-1, 6);
    }

    #[Test]
    public function itWillThrowOutOfBoundsExceptionWhenTryingToSetElementAt(): void
    {
        $sequence = ArraySequence::fromIterable(range(1, 5));

        $this->expectException(IndexOutOfBoundsException::class);
        $sequence->set(10, 6);
    }

    #[Test]
    public function itWillSetAllElementsStartingFromIndexAndReturnTrueIfChanged(): void
    {
        $sequence = ArraySequence::fromIterable(range(1, 5));

        self::assertTrue($sequence->setAll(3, range(6, 9)));
        self::assertEquals([1, 2, 3, 6, 7, 8, 9], $sequence);
        self::assertFalse($sequence->setAll(3, range(6, 9)));
    }
}
