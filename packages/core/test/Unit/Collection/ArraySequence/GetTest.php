<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\ArraySequence;

use Par\Core\Collection\ArraySequence;
use Par\Core\Exception\IndexOutOfBoundsException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class GetTest extends TestCase
{
    public static function provideWithInvalidIndex(): iterable
    {
        yield 'empty' => [ArraySequence::empty(), 0];
        yield 'count+1' => [ArraySequence::fromIterable([1]), 1];
        yield 'negative' => [ArraySequence::empty(), -1];
    }

    #[Test]
    public function itCanGetElementAtIndex(): void
    {
        $sequencedCollection = ArraySequence::fromIterable(range('a', 'e'));

        self::assertEquals('a', $sequencedCollection->get(0));
        self::assertEquals('b', $sequencedCollection->get(1));
        self::assertEquals('c', $sequencedCollection->get(2));
        self::assertEquals('d', $sequencedCollection->get(3));
        self::assertEquals('e', $sequencedCollection->get(4));
    }

    #[Test]
    #[DataProvider('provideWithInvalidIndex')]
    public function itWillThrowIndexOutOfBoundsExceptionForInvalidIndex(ArraySequence $sequence, int $index): void
    {
        $this->expectException(IndexOutOfBoundsException::class);

        $sequence->get($index);
    }
}
