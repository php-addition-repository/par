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
final class LastIndexOfTest extends TestCase
{
    public static function provideForVector(): iterable
    {
        $sequence = ArraySequence::fromIterable(['a', 'b', 'b', 'b', 'c', 'd']);

        yield 'existing-element' => [$sequence, 'b', 3];
        yield 'unknown-element' => [$sequence, 'z', -1];
        yield 'empty' => [ArraySequence::empty(), 'd', -1];
    }

    #[Test]
    #[DataProvider('provideForVector')]
    public function itCanDetermineIndexOfElement(ArraySequence $sequence, mixed $element, int $expectedIndex): void
    {
        self::assertEquals($expectedIndex, $sequence->lastIndexOf($element));
    }
}
