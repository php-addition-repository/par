<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArraySequence;

use Par\Core\Collection\ArraySequence;
use Par\Core\Exception\NoSuchElementException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class LastTest extends TestCase
{
    #[Test]
    public function itCanGetLastElement(): void
    {
        $sequence = ArraySequence::fromIterable(range('a', 'e'));

        self::assertEquals('e', $sequence->last());
    }

    #[Test]
    public function itWillThrowNoSuchElementWhenEmpty(): void
    {
        $sequence = ArraySequence::empty();

        $this->expectException(NoSuchElementException::class);

        $sequence->last();
    }
}
