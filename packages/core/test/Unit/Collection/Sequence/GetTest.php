<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Sequence;

use Par\Core\Collection\ArraySequence;
use Par\Core\Collection\Sequence;
use Par\Core\Exception\IndexOutOfBoundsException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class GetTest extends TestCase
{
    public static function provideForVector(): iterable
    {
        yield 'Vector' => [ArraySequence::fromIterable(range('a', 'e')), 3, 'd'];
    }

    public static function provideInvalidForVector(): iterable
    {
        yield 'Vector:empty' => [ArraySequence::empty(), 0];
        yield 'Vector:count+1' => [ArraySequence::fromIterable(range(1, 5)), 6];
        yield 'Vector:-1' => [ArraySequence::fromIterable(range(1, 5)), -1];
    }

    #[Test]
    #[DataProvider('provideForVector')]
    public function itCanGetElementAtIndex(
        Sequence $sequencedCollection,
        int $index,
        mixed $expectedElement
    ): void {
        self::assertEquals($expectedElement, $sequencedCollection->get($index));
    }

    #[Test]
    #[DataProvider('provideInvalidForVector')]
    public function itWillThrowIndexOutOfBoundsExceptionForInvalidIndex(Sequence $sequencedCollection, int $index): void
    {
        $this->expectException(IndexOutOfBoundsException::class);

        $sequencedCollection->get($index);
    }
}
