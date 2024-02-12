<?php

declare(strict_types=1);

namespace Par\CoreTest\Unit\Collection\Vector;

use Par\Core\Collection\Vector;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class ConstructorsTest extends TestCase
{
    public static function fromIterableProvider(): iterable
    {
        $upperStringList = range('A', 'F');

        yield 'string[]' => [$upperStringList, $upperStringList];

        $lowerStringList = range('a', 'f');
        yield 'array<string, string>' => [array_combine($lowerStringList, $upperStringList), $upperStringList];

        $generator = static fn(): iterable => yield from range(1, 5);
        yield 'Generator<int>' => [$generator(), range(1, 5)];
    }

    #[Test]
    public function empty(): void
    {
        self::assertEquals(
            [],
            Vector::empty()
        );
    }

    #[Test]
    #[DataProvider('fromIterableProvider')]
    public function fromIterable(iterable $iterable, iterable $expectedIterable): void
    {
        self::assertEquals(
            $expectedIterable,
            Vector::fromIterable($iterable)
        );
    }
}
