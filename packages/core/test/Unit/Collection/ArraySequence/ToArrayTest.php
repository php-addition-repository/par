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
final class ToArrayTest extends TestCase
{
    public static function provideForArrayTransformation(): iterable
    {
        yield 'empty' => [
            ArraySequence::empty(),
            [],
        ];

        yield 'string[]' => [
            ArraySequence::fromIterable(range('a', 'e')),
            range('a', 'e'),
        ];
    }

    /**
     * @param ArraySequence<mixed> $sequence
     */
    #[Test]
    #[DataProvider('provideForArrayTransformation')]
    public function itCanBeTransformedToArray(ArraySequence $sequence, array $expectedArray): void
    {
        self::assertEquals($expectedArray, $sequence->toArray());
    }
}
