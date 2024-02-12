<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\SequencedCollection;

use Par\Core\Collection\SequencedCollection;
use Par\Core\Collection\Vector;
use Par\Core\Exception\NoSuchElementException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class LastTest extends TestCase
{
    public static function provideForVector(): iterable
    {
        yield 'Vector' => [Vector::fromIterable(range('a', 'e')), 'e'];
    }

    public static function provideInvalidForVector(): iterable
    {
        yield 'Vector' => [Vector::empty()];
    }

    #[Test]
    #[DataProvider('provideForVector')]
    public function itCanGetLastElement(SequencedCollection $sequencedCollection, mixed $expectedElement): void
    {
        self::assertEquals($expectedElement, $sequencedCollection->last());
    }

    #[Test]
    #[DataProvider('provideInvalidForVector')]
    public function itWillThrowNoSuchElementWhenEmpty(SequencedCollection $sequencedCollection): void
    {
        $this->expectException(NoSuchElementException::class);

        $sequencedCollection->last();
    }
}
