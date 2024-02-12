<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Collection;

use Par\Core\Collection\Collection;
use Par\Core\Collection\Vector;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CountTest extends TestCase
{
    public static function provideForVector(): iterable
    {
        yield 'Vector:empty' => [Vector::empty(), 0];
        yield 'Vector:not-empty' => [Vector::fromIterable(range(1, 5)), 5];
    }

    /**
     * @param Collection<array-key, mixed> $collection
     */
    #[Test]
    #[DataProvider('provideForVector')]
    public function itIsCountable(Collection $collection, int $expectedCount): void
    {
        self::assertCount($expectedCount, $collection);
    }

    /**
     * @param Collection<array-key, mixed> $collection
     */
    #[Test]
    #[DataProvider('provideForVector')]
    public function itCanDetermineIfItIsEmpty(Collection $collection, int $expectedCount): void
    {
        self::assertSame(0 === $expectedCount, $collection->isEmpty());
    }
}
