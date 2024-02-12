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
final class FirstTest extends TestCase
{
    #[Test]
    public function itCanGetFirstElement(): void
    {
        $sequence = ArraySequence::fromIterable(range('a', 'e'));

        self::assertEquals('a', $sequence->first());
    }

    #[Test]
    public function itWillThrowNoSuchElementWhenEmpty(): void
    {
        $sequence = ArraySequence::empty();

        $this->expectException(NoSuchElementException::class);

        $sequence->first();
    }
}
