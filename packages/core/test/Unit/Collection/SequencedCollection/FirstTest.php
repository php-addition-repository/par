<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\SequencedCollection;

use Par\Core\Collection\ArraySequence;
use Par\Core\Collection\SequencedCollection;
use Par\Core\Exception\NoSuchElementException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class FirstTest extends TestCase
{
    public static function provideForVector(): iterable
    {
        yield 'Vector' => [ArraySequence::fromIterable(range('a', 'e')), 'a'];
    }

    public static function provideInvalidForVector(): iterable
    {
        yield 'Vector' => [ArraySequence::empty()];
    }

    #[Test]
    #[DataProvider('provideForVector')]
    public function itCanGetFirstElement(SequencedCollection $sequencedCollection, mixed $expectedElement): void
    {
        self::assertEquals($expectedElement, $sequencedCollection->first());
    }

    #[Test]
    #[DataProvider('provideInvalidForVector')]
    public function itWillThrowNoSuchElementWhenEmpty(SequencedCollection $sequencedCollection): void
    {
        $this->expectException(NoSuchElementException::class);

        $sequencedCollection->first();
    }
}
