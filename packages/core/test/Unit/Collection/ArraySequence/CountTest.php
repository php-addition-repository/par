<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArraySequence;

use Par\Core\Collection\ArraySequence;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CountTest extends TestCase
{
    public static function provideForCounting(): iterable
    {
        yield 'not-empty' => [ArraySequence::fromIterable(range(1, 5)), 5];
        yield 'empty' => [ArraySequence::empty(), 0];
    }

    #[Test]
    #[DataProvider('provideForCounting')]
    public function itCanBeCounted(ArraySequence $sequence, int $expectedCount): void
    {
        self::assertCount($expectedCount, $sequence);
    }
}
